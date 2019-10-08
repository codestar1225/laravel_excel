<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Payout;
use App\Rank;
use App\User;

class PayoutsController extends Controller
{
    protected $walletTypes = [];
    protected $walletIDs = [];
    protected $user;

    protected function calcPayout($payout)
    {
        $payout['interest_payments'] = ['ETH' => 0, 'FELTA' => 0];
        $payout['override_payments'] = ['ETH' => 0, 'FELTA' => 0];
        $payout['ranking_payments'] = ['ETH' => 0, 'FELTA' => 0];
        $payout['rebates'] = ['ETH' => 0, 'FELTA' => 0];
        foreach ($payout['transactions'] as $t) {
            switch ($t['type']) {
                case 'INT':
                    $payout['interest_payments'][$this->walletTypes[$t['wallet_id']]] = $t['amount'];
                    break;
                case 'OVRINT':
                    $payout['override_payments'][$this->walletTypes[$t['wallet_id']]] = $t['amount'];
                    break;
                case 'RNKINT':
                    $payout['ranking_payments'][$this->walletTypes[$t['wallet_id']]] = $t['amount'];
                    break;
                case 'RBTINT':
                    $payout['rebates'][$this->walletTypes[$t['wallet_id']]] = $t['amount'];
                    break;
            }
        }
        return $payout;
    }

    public function init()
    {
        $this->user = \Auth::user();
        $this->walletIDs = [];
        $this->walletTypes = [];
        foreach ($this->user->wallets as $w) {
            $this->walletIDs[] = $w->id;
            $this->walletTypes[$w->id] = $w->type;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->init();
        $walletIDs = $this->walletIDs;
        $payouts = Payout::with(['transactions' => function ($q) use ($walletIDs) {
            return $q->whereIn('wallet_id', $walletIDs);
        }])->where('created_at', '>', $this->user->created_at)->orderBy('id', 'desc')->get()->toArray();
        $payouts = array_map(array($this, 'calcPayout'), $payouts);
        return view('member.payouts.index', compact('payouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->init();
        $walletIDs = $this->walletIDs;
        $payout = Payout::with(['transactions' => function ($q) use ($walletIDs) {
            return $q->whereIn('wallet_id', $walletIDs);
        }])->findOrFail($id)->toArray();

        $payout = $this->calcPayout($payout);
        $downlines = [];
        $rank = 'Member';
        foreach ($payout['transactions'] as $t) {
            switch ($t['type']) {
                case 'OVRINT':
                    $extraList = explode(',', $t['extra']);
                    foreach ($extraList as $row) {
                        $rowComps = explode("|", $row);
                        $this->createDownlineIfNotExists($downlines, $rowComps[0]);
                        $downlines[$rowComps[0]][$t['type']][$this->walletTypes[$t['wallet_id']]] = $rowComps[1];
                    }
                    break;
                case 'RNKINT':
                    $extraComps = explode('#', $t['extra']);
                    if ($rank == 'Member') {
                        $rank = Rank::where('id', $extraComps[0])->first()->label;
                    }
                    $extraList = explode(',', $extraComps[1]);
                    foreach ($extraList as $row) {
                        $rowComps = explode("|", $row);
                        $this->createDownlineIfNotExists($downlines, $rowComps[0]);
                        $downlines[$rowComps[0]][$t['type']][$this->walletTypes[$t['wallet_id']]] = $rowComps[1];
                    }
                    break;
                case 'RBTINT':
                    $extraList = explode(',', $t['extra']);
                    foreach ($extraList as $row) {
                        $rowComps = explode("|", $row);
                        $this->createDownlineIfNotExists($downlines, $rowComps[0]);
                        $downlines[$rowComps[0]][$t['type']][$this->walletTypes[$t['wallet_id']]] = $rowComps[1];
                    }
                    break;
            }
        }
        $downlineIDs = array_keys($downlines);
        $members = User::whereIn('id', $downlineIDs)->select('id', 'name')->get()->toArray();
        foreach ($members as $m) {
            $downlines[$m['id']]['name'] = $m['name'];
        }
        return view('member.payouts.show', compact('payout', 'rank', 'downlines'));
    }

    protected function createDownlineIfNotExists(&$downlines, $id)
    {
        if (!isset($downlines[$id])) {
            $downlines[$id] = [
                'name' => '',
                'OVRINT' => ['ETH' => 0, 'FELTA' => 0],
                'RNKINT' => ['ETH' => 0, 'FELTA' => 0],
                'RBTINT' => ['ETH' => 0]];
        }
    }
}
