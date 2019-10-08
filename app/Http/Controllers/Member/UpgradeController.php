<?php

namespace App\Http\Controllers\Member;

use App\Approval;
use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Plan;
use App\User;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    public function index(){
        $member = User::with('plan')->where('id', \Auth::id())->first()->toArray();
        $plans = Plan::all();
        $upgrades = [];
        foreach($plans as $p){
            if($member['status'] != 1 || $p->price > $member['plan']['price']){
                $upgrades[] = $p->toArray();
            }
        }
        $approval = Approval::with('ref')->where('type', 'UPPLAN')->where('user_id', $member['id'])->orderBy('id', 'desc')->first();
        if($approval){
            $approval = $approval->toArray();
        }
        return view('member.upgrade', compact('member', 'upgrades', 'approval'));
    }
    
    public function update(Request $request){

        $file = $request->file('txfile');
        $txid = $request->get('txid', '');

        if (!$txid && !$file) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'txid' => ['Missing proof of payment'],
            ]);
            throw $error;
            return;
        }


        $userID = \Auth::id();
        $attachments = [
            'PAYMENT_FILE' => "",
            'PAYMENT_TXID' => "",
        ];
        $approval = Approval::where('type', 'UPPLAN')->where('user_id', $userID)->where('status', 0)->orderBy('id', 'desc')->first();
        $plan = Plan::findOrFail($request->plan_id);
        // Update existing request
        $path = '';
        if($approval){
            if ($approval->content) {
                $attachments = json_decode($approval->content, true);
            }
    
            if ($attachments['PAYMENT_FILE']) {
                \Storage::delete($attachments['PAYMENT_FILE']);
                $attachments['PAYMENT_FILE'] = '';
            }
        }
        else {
            $approval = new Approval();
            $approval->type = 'UPPLAN';
            $approval->user_id = $userID;
        }
        $approval->ref()->associate($plan);

        $file = $request->file('txfile');
        if ($file) {
            $path = $request->file('txfile')->store('attachments');
            $attachments['PAYMENT_FILE'] = $path;
        }
        $attachments['PAYMENT_TXID'] = $txid;
        $approval->content = json_encode(array_merge(['old' => \Auth::user()->plan_id], $attachments));
        $approval->status = 0;
        $approval->save();
        return redirect(route('member.upgrade'))->with('success', 'Purchase submitted');
    }
}
