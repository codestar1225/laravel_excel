<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Plan;
use App\Transaction;
use App\Approval;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class UserActivations
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
        if ($event->user->status == 0 && $event->user->sponsor) {
            $sponsor = $event->user->sponsor;
            $plan = $event->user->plan;
            $sponsorWallets = $sponsor->wallets;
            $canActivate = false;
            $theWallet = null;
            // 2019-06-26 KW: Rule changed, all new registrations require approval
            /*foreach ($sponsorWallets as $wallet) {
                if ($wallet->type == $plan->price_type) {
                    $canActivate = $wallet->balance >= $plan->price;
                    $theWallet = $wallet;
                    break;
                }
            }*/

            if ($canActivate) {
                $event->user->status = 1;
                $event->user->save();

                $tx = new Transaction();
                $tx->type = 'SPONSOR';
                $tx->wallet()->associate($theWallet);
                $tx->ref()->associate($event->user);
                $tx->amount = -$plan->price;
                $tx->save();
            } else {
                $approval = new Approval();
                $approval->type = 'SPONSOR';
                $approval->user()->associate($event->user->sponsor);
                $approval->ref()->associate($event->user);
                $approval->save();
            }

            Mail::to($event->user->email)->bcc('admin@feltacoin.com','feltacoin@gmail.com','management@vrlive.asia','lauyoongloon@gmail.com')->send((new WelcomeMail($event->user, $event->rawpassword))->subject('Welcome to FELTA LONDON CAPITAL'));
        }
    }
}
