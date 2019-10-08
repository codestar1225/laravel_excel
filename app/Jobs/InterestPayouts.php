<?php

namespace App\Jobs;

use App\Approval;
use App\OverrideBonusSetting;
use App\Payout;
use App\Plan;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InterestPayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payout;
    public $interestPayouts;
    public $totalInterest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payout)
    {
        $this->payout = $payout;
        $this->interestPayouts = [];
        $this->totalInterest = 0;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payout = $this->payout;
        $interestRate = $payout->rate / 100;
        $feltaRatio = floatval($payout->extra);

        $users = User::active()->where('role', 'member')->where('plan_id', '>', 0)
            ->select('*')
            ->addSelect(\DB::raw('(select w.id FROM wallets w WHERE w.user_id=users.id AND type="ETH") as wallet_eth_id'))
            ->addSelect(\DB::raw('(select w.id FROM wallets w WHERE w.user_id=users.id AND type="FELTA") as wallet_felta_id'))
            ->addSelect(\DB::raw('(select count(u2.id) FROM users u2 WHERE u2.sponsor_id=users.id AND u2.status=1) as dcount'))
            ->with('rank')
            ->get()->toArray();

        $companyWallets = User::active()->where('username', 'company')
            ->addSelect(\DB::raw('(select w.id FROM wallets w WHERE w.user_id=users.id AND type="ETH") as wallet_eth_id'))
            ->addSelect(\DB::raw('(select w.id FROM wallets w WHERE w.user_id=users.id AND type="FELTA") as wallet_felta_id'))
            ->first()->toArray();

        $plans = [];
        $plansRaw = Plan::all();
        foreach ($plansRaw as $p) {
            $plans[strval($p->id)] = $p;
        }

        $this->payInterest($plans, $interestRate, $feltaRatio, $users, $companyWallets);
        $this->payOverrides($plans, $interestRate, $feltaRatio, $users, $companyWallets);
        if ($this->totalInterest > 0) {
            $this->payRanking($plans, $interestRate, $feltaRatio, $users, $companyWallets);
        }
        $this->payRebates($feltaRatio, $users);
    }

    protected function payRebates($feltaRatio, $users)
    {
        if (strlen($this->payout->rebate_extra) == 0 || $this->payout->rebate_rate == 0) {
            return;
        }
        $wallets = [];
        foreach ($users as $u) {
            $wallets[$u['id']] = $u['wallet_eth_id'];
        }
        \DB::beginTransaction();
        try {
            $totalRebates = 0;
            $members = explode(',', $this->payout->rebate_extra);
            $newMemberTime = new Carbon('2010-01-01');
            $prevPayout = Payout::where('id', '<', $this->payout->id)->latest()->first();
            if ($prevPayout) {
                $newMemberTime = $prevPayout->created_at;
            }

            $newApprovals = Approval::with(['ref', 'ref.plan'])->where('status', '1')
                ->where('type', 'SPONSOR')->where('updated_at', '>', $newMemberTime)->get();
            $eligibles = [];
            foreach ($newApprovals as $approval) {
                if (!$approval->ref) {
                    continue;
                }
                // ETH amount
                //$amount = $approval->ref->plan->price * $this->payout->rebate_rate / 100 * $feltaRatio;
                $amount = $approval->ref->plan->price * $this->payout->rebate_rate / 100;
                $parents = explode(",", $approval->ref->parents);

                foreach ($parents as $pid) {
                    if (strlen($pid) > 0 && in_array($pid, $members)) {
                        if (!isset($eligibles[$pid])) {
                            $eligibles[$pid] = [
                                'amount' => 0,
                                'members' => [],
                            ];
                        }

                        $totalRebates += $amount;
                        $eligibles[$pid]['amount'] = $eligibles[$pid]['amount'] + $amount;
                        $eligibles[$pid]['members'][] = $approval->ref->id . '|' . $amount;
                    }
                }

            }
            foreach ($eligibles as $k => $v) {
                $wallet = $wallets[$k];
                if ($wallet) {
                    $tx = new Transaction();
                    $tx->type = "RBTINT";
                    $tx->wallet_id = $wallet;
                    $tx->amount = $v['amount'];
                    $tx->ref_type = 'App\Payout';
                    $tx->ref_id = $this->payout->id;
                    $tx->extra = implode(',', $v['members']);
                    $tx->save();
                }
            }
            $this->payout->rebates = $totalRebates;
            $this->payout->save();
            \DB::commit();
        } catch (Exception $ex) {
            \DB::rollBack();
        }
    }

    protected function payRanking($plans, $interestRate, $feltaRatio, $users, $companyWallets)
    {
        \DB::beginTransaction();
        try {
            $totalRanking = 0;
            foreach ($users as $user) {
                if ($user['rank_id'] <= 1 || intval($user['dcount']) == 0) {
                    continue;
                }

                // Find downlines
                $find = ',' . $user['id'] . ',';
                $downlines = array_filter($this->interestPayouts, function ($v, $k) use ($find) {
                    return stripos($v['parents'], $find) > -1;
                }, ARRAY_FILTER_USE_BOTH);

                $ethBonus = 0;
                $feltaBonus = 0;
                $downlineQEth = [];
                $downlineQFelta = [];
                $bonusRate = floatval($user['rank']['bonus']) / 100;
                foreach ($downlines as $d) {
                    $eb = $this->interestPayouts[$d['id']]['ETH'] * $bonusRate;
                    $fb = $this->interestPayouts[$d['id']]['FELTA'] * $bonusRate;

                    $ethBonus += $eb;
                    //$feltaBonus += $fb;

                    $downlineQEth[] = $d['id'] . '|' . $eb;
                    $downlineQFelta[] = $d['id'] . '|' . $fb;
                }
                $totalRanking += $ethBonus + ($feltaBonus / $feltaRatio);
                $downlineQEthStr = implode(',', $downlineQEth);
                $downlineQFeltaStr = implode(',', $downlineQFelta);

                if ($ethBonus > 0 && $user['wallet_eth_id']) {
                    $tx = new Transaction();
                    $tx->type = "RNKINT";
                    $tx->wallet_id = $user['wallet_eth_id'];
                    $tx->amount = $ethBonus;
                    $tx->ref_type = 'App\Payout';
                    $tx->ref_id = $this->payout->id;
                    $tx->extra = $user['rank_id'] . "#" . $downlineQEthStr;
                    $tx->save();
                }

                if ($feltaBonus > 0 && $user['wallet_felta_id']) {
                    $tx = new Transaction();
                    $tx->type = "RNKINT";
                    $tx->wallet_id = $user['wallet_felta_id'];
                    $tx->amount = $feltaBonus;
                    $tx->ref_type = 'App\Payout';
                    $tx->ref_id = $this->payout->id;
                    $tx->extra = $user['rank_id'] . '#' . $downlineQFeltaStr;
                    $tx->save();
                }
            }

            $this->payout->ranking = $totalRanking;
            $this->payout->save();
            \DB::commit();
        } catch (Exception $ex) {
            \DB::rollBack();
        }
    }

    protected function payOverrides($plans, $interestRate, $feltaRatio, $users, $companyWallets)
    {
        $settings = OverrideBonusSetting::orderBy('sponsor', 'asc')->get();
        \DB::beginTransaction();
        $totalOverrides = 0;
        try {
            foreach ($users as $user) {
                if (intval($user['dcount']) == 0) {
                    continue;
                }

                // Prepare bonus matrix for users
                $user['dcount'] = intval($user['dcount']);

                $plan = $plans[strval($user['plan_id'])];
                $validBonuses = [];
                foreach ($settings as $s) {
                    if ($s->invest > $plan->price || $s->sponsor > $user['dcount']) {
                        break;
                    }
                    $validBonuses[strval($s->level)] = $s->bonus / 100;
                }

                $bonuses = [];
                // Find downlines
                $find = ',' . $user['id'] . ',';
                $downlines = array_filter($this->interestPayouts, function ($v, $k) use ($find) {
                    return stripos($v['parents'], $find) > -1;
                }, ARRAY_FILTER_USE_BOTH);
                $downlineQEth = [];
                $downlineQFelta = [];
                $ethBonus = 0;
                $feltaBonus = 0;
                foreach ($downlines as $d) {
                    $level = $this->getLevelFor($d['parents'], $user['id']);

                    if (isset($validBonuses[$level])) {
                        $bonusRate = $validBonuses[$level];

                        $eb = $this->interestPayouts[$d['id']]['ETH'] * $bonusRate;
                        $fb = $this->interestPayouts[$d['id']]['FELTA'] * $bonusRate;
                        $ethBonus += $eb;
                        $feltaBonus += $fb;

                        $downlineQEth[] = $d['id'] . '|' . $eb;
                        $downlineQFelta[] = $d['id'] . '|' . $fb;
                    }
                }
                $downlineQEthStr = implode(',', $downlineQEth);
                $downlineQFeltaStr = implode(',', $downlineQFelta);

                $totalOverrides += $ethBonus + ($feltaBonus / $feltaRatio);

                if ($ethBonus > 0 && $user['wallet_eth_id']) {
                    $tx = new Transaction();
                    $tx->type = "OVRINT";
                    $tx->wallet_id = $user['wallet_eth_id'];
                    $tx->amount = $ethBonus;
                    $tx->ref_type = 'App\Payout';
                    $tx->ref_id = $this->payout->id;
                    $tx->extra = $downlineQEthStr;
                    $tx->save();
                }

                if ($feltaBonus > 0 && $user['wallet_felta_id']) {
                    $tx = new Transaction();
                    $tx->type = "OVRINT";
                    $tx->wallet_id = $user['wallet_felta_id'];
                    $tx->amount = $feltaBonus;
                    $tx->ref_type = 'App\Payout';
                    $tx->ref_id = $this->payout->id;
                    $tx->extra = $downlineQFeltaStr;
                    $tx->save();
                }
            }
            $this->payout->override = $totalOverrides;
            $this->payout->save();
            \DB::commit();
        } catch (Exception $ex) {
            \DB::rollBack();
        }
    }

    protected function getLevelFor($pKeys, $sponsorID)
    {
        $sponsorID = strval($sponsorID);
        $parents = explode(',', $pKeys);
        $parents = array_filter($parents);
        $level = 1;
        $sponsorIdx = 0;
        foreach ($parents as $p) {
            if ($p == $sponsorID) {
                break;
            } else {
                $sponsorIdx++;
            }
        }
        $level = count($parents) - $sponsorIdx;
        return $level;
    }

    protected function payInterest($plans, $interestRate, $feltaRatio, $users, $companyWallets)
    {
        \DB::beginTransaction();
        try
        {
            $this->totalInterest = 0;
            $totalCompanyInterest = 0;
            $totalCompanyInterestFelta = 0;

            foreach ($users as $user) {
                try {
                    $plan = $plans[strval($user['plan_id'])];
                    $interest = $plan->price * $interestRate;
                    $userInterest = $interest * $plan->user_comm / 100;
                    $this->totalInterest += $userInterest;

                    // For transaction
                    $ethInterest = $userInterest;// / 2;
                    $feltaInterest = 0;//$ethInterest * $feltaRatio;

                    $companyInterest = ($interest * $plan->company_comm / 100);// / 2;
                    $totalCompanyInterest += $companyInterest;
                    //$totalCompanyInterestFelta += $companyInterest * $feltaRatio;

                    $this->interestPayouts[$user['id']] = [
                        'id' => $user['id'],
                        'ETH' => 0,
                        'FELTA' => 0,
                        'parents' => $user['parents'],
                    ];

                    if ($ethInterest > 0 && $user['wallet_eth_id']) {
                        $tx = new Transaction();
                        $tx->type = "INT";
                        $tx->wallet_id = $user['wallet_eth_id'];
                        $tx->amount = $ethInterest;
                        $tx->ref_type = 'App\Payout';
                        $tx->ref_id = $this->payout->id;
                        $tx->extra = $plan->price;
                        $tx->save();
                        $this->interestPayouts[$user['id']]['ETH'] = $ethInterest;
                    }

                    if ($feltaInterest > 0 && $user['wallet_felta_id']) {
                        $tx = new Transaction();
                        $tx->type = "INT";
                        $tx->wallet_id = $user['wallet_felta_id'];
                        $tx->amount = $feltaInterest;
                        $tx->ref_type = 'App\Payout';
                        $tx->ref_id = $this->payout->id;
                        $tx->extra = $plan->price;
                        $tx->save();

                        $this->interestPayouts[$user['id']]['FELTA'] = $feltaInterest;
                    }
                } catch (Exception $ex) {
                }
            }

            //Company interest
            $tx = new Transaction();
            $tx->type = "COMINT";
            $tx->wallet_id = $companyWallets['wallet_eth_id'];
            $tx->amount = $totalCompanyInterest;
            $tx->ref_type = 'App\Payout';
            $tx->ref_id = $this->payout->id;
            $tx->extra = '';
            $tx->save();

            $tx = new Transaction();
            $tx->type = "COMINT";
            $tx->wallet_id = $companyWallets['wallet_felta_id'];
            $tx->amount = $totalCompanyInterestFelta;
            $tx->ref_type = 'App\Payout';
            $tx->ref_id = $this->payout->id;
            $tx->extra = '';
            $tx->save();
            
            // Payout summary
            $this->payout->interest = $this->totalInterest;
            $this->payout->save();

            \DB::commit();
        } catch (Exception $ex) {
            \DB::rollBack();
        }
    }
}
