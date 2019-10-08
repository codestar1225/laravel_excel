<?php

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
 */
use App\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
Artisan::command('ranking', function () {
    \App\Jobs\Ranking::dispatch();
})->describe('Calculate rankings');

Artisan::command('test', function () {
    Mail::to('chooikw@gmail.com')->bcc('admin@feltacoin.com')->send((new WelcomeMail(\App\User::findOrFail(1), 'abc'))->subject('Welcome to FELTA LONDON CAPITAL'));
    
})->describe('Test function');

Artisan::command('delpayout {id}', function ($id) {
    $p = \App\Payout::findOrFail($id);
    $txids = \App\Transaction::where('ref_type', 'App\Payout')->where('ref_id', $p->id)->pluck('id')->toArray();
    \App\Transaction::destroy($txids);
    $p->delete();
    print("Deleted payout ". $id);
})->describe('Delete Payout');

/*
Artisan::command('batchpin', function () {
    $members = User::where('id', '>', 3)->get();
    foreach($members as $m){
        $m->withdrawkey = strtoupper(str_random(6));
        $m->save();
        \Mail::to($m->email)->send((new \App\Mail\ResetSecurityPinMail($m))->subject('New Security Pin'));
    }
})->describe('Reset security pin');
*/