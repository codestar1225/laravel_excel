<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTransactionWallets
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TransactionCreated/TransactionDeleted  $event
     * @return void
     */
    public function handle($event)
    {
        $wallet = $event->transaction->wallet;
        if($wallet){
            if($event->isAdd){
                $wallet->balance = $wallet->balance + $event->transaction->amount;
            }
            else{
                $wallet->balance = $wallet->balance - $event->transaction->amount;
            }
        }
        $wallet->save();
    }
}
