<?php

namespace App\Http\Controllers\Member;

use App\Approval;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\Transfer;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;

class ClosingController extends Controller
{
    public function close(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'address' => 'required'
        ]);
    
        if ($validator->fails()) {
            return redirect(route('profile.index').'#closing')
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = \Auth::user();
        $user->status = 3;
        $user->save();

        $approval = new Approval();
        $approval->type = 'CLOSE';
        $approval->content = json_encode(['address' => $request->get('address')]);
        $approval->user()->associate($user);
        $approval->ref()->associate($user);
        $approval->save();
        return redirect(route('profile.index'))->with('success', 'Request submitted.');
    }

    public function cancel()
    {
        $user = \Auth::user();
        $user->status = 1;
        $user->save();
        
        $approval = Approval::where('user_id', $user->id)->where('status', 0)->where('type', 'CLOSE');
        $approval->delete();
        return redirect(route('profile.index'))->with('success', 'Request cancelled.');
    }
}
