<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Wallet;
use Illuminate\Support\Facades\DB;
use Liliom\Inbox\Models\Participant;

class DashboardController extends Controller
{
    function index(){
        $userID = \Auth::id();
        $user = User::select('*')->where('id', $userID)->with('wallets')->with('plan')->with('rank')->withDownlinesQty()->withGroupSales()->first();

        $felta = 0;
        $eth = 0;

        $ethWallet = null;
        $feltaWallet = null;
        $walletIDs = [];
        foreach($user->wallets as $w){
            $walletIDs[] = $w->id;
            if($w->type == 'ETH')
            {
                $eth = $w->balance;
                $ethWallet = $w->id;
            }
            else
            {
                $felta = $w->balance;
                $feltaWallet = $w->id;
            }
        }

        $ethIncome = 0;
        $feltaIncome = 0;
        $transactions = DB::table('transactions')    
            ->addselect(DB::Raw('sum(amount) as total'))
            ->addselect(['wallet_id'])
            ->whereIn('wallet_id', $walletIDs)
            ->groupBy('wallet_id')
            ->where('type', 'like', '%INT')
            ->get();
        foreach($transactions as $tx){
            if($tx->wallet_id == $ethWallet){
                $ethIncome += $tx->total;
            }
            else if($tx->wallet_id == $feltaWallet){
                $feltaIncome += $tx->total;
            }
        }

        $downlines = $user->downlines_qty;
        $sales = $user->group_sales;
        $rank = ($user->rank && $user->rank->id > 1) ? $user->rank->label : "Member";
        $plan = $user->plan ? $user->plan->label : "-";

        $announcement = null;
        $participant = Participant::inbox(2)->with('thread')->latest()->first();
        if($participant)
        {
            $announcement = ['subject' => $participant->thread->subject, 'body' => $message = $participant->thread->lastMessage()->body, 'created_at' => $participant->thread->created_at];
        }

        $reflink = route('register', ['ref' => $user->referralkey]);
        return view('member.dashboard', compact('felta', 'eth', 'downlines', 'sales', 'ethIncome', 'feltaIncome', 'rank', 'plan', 'announcement', 'reflink'));
    }
}
