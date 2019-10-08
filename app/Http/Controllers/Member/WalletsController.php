<?php

namespace App\Http\Controllers\Member;

use App\Approval;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Transaction;
use App\Transfer;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;

class WalletsController extends Controller
{
    public function transactions()
    {
        $userID = \Auth::id();
        $transactions = Transaction::join('wallets', 'wallets.id', '=', 'transactions.wallet_id')
            ->select('transactions.*', 'wallets.type as wallet_type')
            ->where('wallets.user_id', $userID)->orderBy('transactions.id', 'desc')->get()->toArray();
        $wallets = Wallet::where('user_id', $userID)->orderBy('id', 'asc')->get()->toArray();
        return view('member.wallets.transactions', compact('transactions', 'wallets'));
    }

    public function withdrawals()
    {
        $user = \Auth::user();
        $userID = $user->id;
        $wallet = Wallet::where('user_id', $userID)->where('type', 'ETH')->first()->toArray();
        $records = Approval::where('user_id', $userID)->where('type', 'WITHDRAWAL')->orderBy('id', 'desc')->get()->toArray();
        foreach ($records as $k => $v) {
            $v['content'] = json_decode($v['content'], true);
            if (!isset($v['content']['address'])) {
                $v['content']['address'] = '';
            }
            $records[$k] = $v;
        }
        $fee = Setting::where('option', 'withdrawal_fee')->first()->val;

        $kycStatus = $user->kyc_status;
        return view('member.wallets.withdrawals', compact('wallet', 'records', 'fee', 'kycStatus'));
    }

    public function transfers()
    {
        $records = [];
        $user = \Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->where('type', 'FELTA')->first()->toArray();
        $recordsFrom = Transfer::with(['toWallet', 'toWallet.user'])->where('from_wallet_id', $wallet['id'])->orderBy('id', 'desc')->get()->toArray();
        foreach ($recordsFrom as $k => $v) {
            $v['type'] = 'OUT';
            $records[] = $v;
        }
        $recordsFrom = Transfer::with(['fromWallet', 'fromWallet.user'])->where('to_wallet_id', $wallet['id'])->orderBy('id', 'desc')->get()->toArray();
        foreach ($recordsFrom as $k => $v) {
            $v['type'] = 'IN';
            $records[] = $v;
        }
        $kycStatus = $user->kyc_status;
        return view('member.wallets.transfers', compact('wallet', 'records', 'kycStatus'));
    }

    public function transferout(Request $request)
    {
        $amount = floatval($request->get('amount', 0));
        if ($amount <= 0) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'amount' => ['Invalid amount'],
            ]);
            throw $error;
        }

        $recipient = User::where('username', $request->get('recipient'))->first();
        if (!$recipient) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'recipient' => ['Invalid recipient'],
            ]);
            throw $error;
        }

        $user = \Auth::user();
        if ($user->kyc_status == "0") {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'amount' => ['Please complete KYC'],
            ]);
            throw $error;
        }
        $wallet = Wallet::where('user_id', $user->id)->where('type', 'FELTA')->firstOrFail();
        if ($wallet->balance < $amount) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'amount' => ['Insufficient balance'],
            ]);
            throw $error;
        }

        $toWallet = Wallet::where('user_id', $recipient->id)->where('type', 'FELTA')->first();

        $transfer = new Transfer();
        $transfer->fromWallet()->associate($wallet);
        $transfer->toWallet()->associate($toWallet);
        $transfer->from_amount = $amount;
        $transfer->to_amount = $amount;
        $transfer->save();

        $tx = new Transaction();
        $tx->type = 'TXOUT';
        $tx->wallet()->associate($wallet);
        $tx->ref()->associate($transfer);
        $tx->amount = -$amount;
        $tx->save();

        $tx = new Transaction();
        $tx->type = 'TXIN';
        $tx->wallet()->associate($toWallet);
        $tx->ref()->associate($transfer);
        $tx->amount = $amount;
        $tx->save();

        return redirect(route('member.wallets.transfers'))->with('success', 'Transfer successful.');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'numeric|min:0.01',
            'address' => 'required',
            'security_pin' => 'required',
        ], ['address.required' => 'The ETH wallet address is required.']);
        $amount = floatval($request->get('amount', 0));
        if ($amount <= 0) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'amount' => ['Invalid amount'],
            ]);
            throw $error;
        }
        $user = \Auth::user();
        $pin = $request->get("security_pin", "");
        if ($pin != $user->withdrawkey) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'amount' => ['Invalid security pin'],
            ]);
            throw $error;
        }
        $wallet = Wallet::where('user_id', $user->id)->where('type', 'ETH')->first();
        $fee = floatval(Setting::where('option', 'withdrawal_fee')->first()->val) / 100 * $amount;

        if ($amount < $fee) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'amount' => ['Amount is less than withdrawal fees'],
            ]);
            throw $error;
        }

        if ($wallet->balance < $amount) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'amount' => ['Insufficient balance'],
            ]);
            throw $error;
        }

        $approval = new Approval();
        $approval->type = 'WITHDRAWAL';
        $approval->user()->associate($user);
        $approval->ref()->associate($wallet);
        $approval->content = '{"amount":' . $amount . ', "address": "' . $request->get('address', '') . '", "fee":' . $fee . '}';
        $approval->save();

        $tx = new Transaction();
        $tx->type = 'WITHDRAWAL';
        $tx->wallet()->associate($wallet);
        $tx->ref()->associate($user);
        $tx->amount = -$amount;
        $tx->save();

        return redirect(route('member.wallets.withdrawals'))->with('success', 'Request submitted for approval.');
    }
}
