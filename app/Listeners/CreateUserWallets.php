<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Wallet;

class CreateUserWallets
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        Wallet::Create([
            'user_id' => $event->user->id,
            'type' => 'ETH'
        ]);
        Wallet::Create([
            'user_id' => $event->user->id,
            'type' => 'FELTA'
        ]);
    }
}
