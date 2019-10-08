<?php

namespace App\Http\Controllers;

use App\Approval;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $member = \App\User::select("*")->where('id', \Auth::id())
            ->addSelect(\DB::raw("(select content from approvals where approvals.user_id=users.id AND type='KYC' order by approvals.id DESC LIMIT 1) as kyccontent"))
            ->addSelect(\DB::raw("(select status from approvals where approvals.user_id=users.id AND type='KYC' order by approvals.id DESC LIMIT 1) as kycstatus"))
            ->with('plan')->with('wallets')->with('sponsor')->withDownlinesQty()->firstOrFail()->toArray();
        $paymentAddress = "";
        $attachments = [
            'PAYMENT_FILE' => "",
            'PAYMENT_TXID' => "",
        ];
        $view = 'member.profile';
        if ($member['role'] == 'admin') {
            $view = 'admin.profile';
        }

        $upgrades = [];

        $isOwn = true;
        $attachments = [];
        $paymentAddress = "";
        if ($member['status'] == 0) {
            $paymentAddress = \App\Setting::where('option', 'company_eth_wallet')->first()->val;

            $attachments = [
                'PAYMENT_FILE' => "",
                'PAYMENT_TXID' => "",
            ];
            $approval = \App\Approval::where('type', 'SPONSOR')->where('ref_id', $member['id'])->where('ref_type', 'App\User')->first();
            if ($approval) {
                $attachments = json_decode($approval->content, true);
            }
        }

        return view($view, compact('member', 'isOwn', 'attachments', 'paymentAddress'));
    }

    public function kyc(Request $request)
    {
        $file = $request->file('file');
        if (!$file) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'file' => ['Missing proof of ID'],
            ]);
            throw $error;
            return;
        }

        $path = $file->store('attachments');

        $user = \Auth::user();
        $approval = \App\Approval::where("type", "KYC")->where("user_id", $user->id)->where('status', 0)->first();
        if (!$approval) {
            $approval = new \App\Approval();
            $approval->type = "KYC";
            $approval->user_id = $user->id;
            $approval->ref()->associate($user);
        }
        $approval->content = $path;
        $approval->save();
        return redirect(route('profile.index') . '#kyc');
    }

    public function payment(Request $request)
    {
        $user = \Auth::user();
        $attachments = [
            'PAYMENT_FILE' => "",
            'PAYMENT_TXID' => "",
        ];
        $approval = Approval::where('type', 'SPONSOR')->where('ref_id', $user->id)->where('ref_type', 'App\User')->firstOrFail();
        if ($approval->content) {
            $attachments = json_decode($approval->content, true);
        }

        if ($attachments['PAYMENT_FILE']) {
            \Storage::delete($attachments['PAYMENT_FILE']);
            $attachments['PAYMENT_FILE'] = '';
        }

        $file = $request->file('txfile');
        if ($file) {
            $path = $request->file('txfile')->store('attachments');
            $attachments['PAYMENT_FILE'] = $path;
        }

        $attachments['PAYMENT_TXID'] = $request->get('txid', '');
        $approval->content = json_encode($attachments);
        $approval->save();

        return redirect(route('profile.index') . '#payment')->with('success', 'Proof of payment submitted');
    }

    public function update(Request $request)
    {
        $type = $request->get('type');
        $user = \Auth::user();
        
        $page = "passwords";
        $this->redirect = route('profile.index').'#securitypin';
        if($type == "security_pin"){
            $page = "securitypin";
            $request->validate([
                'cpassword' => 'required',
                'password' => 'required',
                'password' => 'required|confirmed|min:6|max:12',
            ], [
                'password.min' => "The security pins must be at least 6 characters.",
                'password.max' => "The security pins must be less than 13 characters.",
                'cpassword.required' => "The current security pin field is required.",
                'password.required' => "The security pin field is required.",
                'password.confirmed' => "The security pins do not match."
            ]);

            if ($user->withdrawkey != $request->get('cpassword')) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'cpassword' => ['Invalid security pin'],
                ]);
                throw $error;
            }

            $user->withdrawkey = $request->get('password');
        }
        else
        {
            $request->validate([
                'cpassword' => 'required',
                'password' => 'required',
                'password' => 'required|confirmed|min:8',
            ], [
                'cpassword.required' => "The current password field is required.",
            ]);

            if (!\Hash::check($request->get('cpassword'), $user->password)) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'cpassword' => ['Invalid current password'],
                ]);
                throw $error;
            }
            $user->password = bcrypt($request->get('password'));
        }
        
        
        
        $user->save();

        return redirect(route('profile.index').'#page')->with('success', ($type == "security_pin" ? 'Security Pin' : 'Password').' updated');
    }
}
