<?php

namespace App\Jobs;

use App\Wallet;
use App\Plan;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PlanReward implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $plan;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $plan)
    {
        $this->user = $user;
        $this->plan = $plan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->plan->bonus > 0){
            $wallet = Wallet::where('user_id', $this->user->id)->where('type', 'FELTA')->first();
            if($wallet){
                $prevBonus = floatval(Transaction::where('type', 'INVPLN')
                                ->where('wallet_id', $wallet->id)
                                ->latest()
                                ->pluck('extra')
                                ->first());
                $bonus = $this->plan->bonus - $prevBonus;
                if($bonus > 0){
                    $tx = new Transaction();
                    $tx->type = "INVPLN";
                    $tx->wallet_id = $wallet->id;
                    $tx->amount = $bonus;
                    $tx->ref_type = 'App\Plan';
                    $tx->ref_id = $this->plan->id;
                    $tx->extra = $this->plan->bonus;
                    $tx->save();
                }
            }
        }
    }
}
