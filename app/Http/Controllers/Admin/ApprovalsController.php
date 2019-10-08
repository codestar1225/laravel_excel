<?php

namespace App\Http\Controllers\Admin;

use App\Approval;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Transaction;
use App\Jobs\PlanReward;
use Illuminate\Http\Request;

class ApprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function members()
    {
        $approvals = Approval::where('type', 'SPONSOR')->with('ref')->with('ref.plan')->with('user')->orderBy('id', 'desc')->get()->toArray();
        foreach ($approvals as $k => $a) {
            if ($a['content']) {
                $a['content'] = json_decode($a['content'], true);
            }
            $approvals[$k] = $a;
        }
        return view('admin.approvals.members', compact('approvals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showmember($id)
    {
        $approval = Approval::with('ref')->findOrFail($id);
        $member = $approval->ref;
        $attachments = [];
        if ($approval) {
            $attachments = json_decode($approval->content, true);
        }
        return view('admin.approvals.showmember', compact('member', 'attachments', 'approval'));
    }

    public function updatemember(Request $request, $id)
    {
        $approval = Approval::with(['ref', 'ref.plan'])->findOrFail($id);
        $approval->status = intval($request->get('status', 0));
        if ($approval->status == 1) {
            $approval->ref->status = 1;
            $approval->ref->save();
            PlanReward::dispatch($approval->ref, $approval->ref->plan);
        } else if ($approval->status == 2) {
            $approval->ref->status = 2;
            $approval->ref->save();
        }
        $approval->admin_id = \Auth::id();
        $approval->save();
        return redirect(route('admin.approvals.members'))->with('success', 'Approval has been updated');
    }

    public function deletemember($id)
    {
        $approval = Approval::with('ref')->findOrFail($id);
        $member = $approval->ref->delete();
        $approval->delete();

        return redirect(route('admin.approvals.members'))->with('success', 'Member has been deleted');
    }

    public function withdrawals()
    {
        $approvals = Approval::where('type', 'WITHDRAWAL')->with('user')->orderBy('id', 'desc')->get()->toArray();
        foreach ($approvals as $k => $a) {
            if ($a['content']) {
                $a['content'] = json_decode($a['content'], true);
            }
            $approvals[$k] = $a;
        }
        return view('admin.approvals.withdrawals', compact('approvals'));
    }

    public function withdrawalpayment($id)
    {
        return view('admin.approvals.withdrawalpayment', compact('id'));
    }

    public function updatewithdrawal(Request $request, $id)
    {
        $approval = Approval::with('ref')->findOrFail($id);
        $approval->status = intval($request->get('status', 0));

        $content = json_decode($approval->content, true);

        if ($approval->status == 1) {
            $txid = $request->get('txid', '');
            $file = $request->file('txfile');
            if (!$txid && !$file) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'txid' => ['Missing proof of payment'],
                ]);
                throw $error;
                return;
            }

            $content = json_decode($approval->content, true);
            $content['PAYMENT_TXID'] = $txid;
            if ($file) {
                $path = $file->store('attachments');
                $content['PAYMENT_FILE'] = $path;
            }
            $approval->content = json_encode($content);
            $approval->save();
        } else if ($approval->status == 2) {
            $tx = new Transaction();
            $tx->type = 'WITHDRAWAL_REVERSE';
            $tx->wallet()->associate($approval->ref);
            $tx->ref()->associate($approval);
            $tx->amount = floatval($content['amount']);
            $tx->save();
        }
        $approval->admin_id = \Auth::id();
        $approval->save();
        return redirect(route('admin.approvals.withdrawals'))->with('success', 'Approval has been updated');
    }

    public function closings()
    {
        $approvals = Approval::where('type', 'CLOSE')->with(['user', 'user.wallets', 'user.plan', 'user.sponsor'])->orderBy('id', 'desc')->get()->toArray();
        foreach ($approvals as $k => $a) {
            $a['ETH_wallet'] = 0;
            $a['FELTA_wallet'] = 0;
            foreach ($a['user']['wallets'] as $w) {
                $a[$w['type'] . '_wallet'] = $w['balance'];
            }
            $approvals[$k] = $a;
        }
        return view('admin.approvals.closings', compact('approvals'));
    }

    public function editclosing($id)
    {
        $approval = Approval::with(['user', 'user.sponsor', 'user.plan', 'user.wallets'])->findOrFail($id)->toArray();
        $approval['ETH_wallet'] = 0;
        $approval['FELTA_wallet'] = 0;
        foreach ($approval['user']['wallets'] as $w) {
            $approval[$w['type'] . '_wallet'] = $w['balance'];
        }
        if ($approval['content']) {
            $approval['content'] = $this->decodeClosingContent($approval['content']);
        }
        return view('admin.approvals.editclosing', compact('approval'));
    }

    protected function decodeClosingContent($content)
    {
        $address = '';
        $feePct = 0;
        $fee = 0;
        $comps = json_decode($content, true);
        if (isset($comps['address'])) {
            $address = $comps['address'];
        }
        if (isset($comps['feePct'])) {
            $feePct = $comps['feePct'];
        }
        if (isset($comps['fee'])) {
            $fee = $comps['fee'];
        }
        return ['address' => $address, 'fee' => $fee, 'feePct' => $feePct];
    }

    public function updateclosing(Request $request, $id)
    {
        $approval = Approval::with(['user', 'user.wallets'])->findOrFail($id);
        $approval->status = intval($request->get('status', 0));
        $content = $this->decodeClosingContent($approval->content);
        if ($approval->status == 1) {
            $approval->user->status = 4;
            $feePct = floatval($request->get('fee'));

            if ($feePct > 0) {
                $tx = new Transaction();
                $tx->type = 'CLOSING_FEE';
                $wallet = null;
                $balance = 0;
                foreach ($approval->user->wallets as $wallet) {
                    if ($wallet->type == 'ETH') {
                        $tx->wallet()->associate($wallet);
                        break;
                    }
                }
                $fee = round($wallet->balance * $feePct / 100, 4);
                $tx->ref()->associate($approval);
                $tx->amount = ($tx->amount-$fee);
                $tx->save();

                $content['fee'] = $fee;
                $content['feePct'] = $feePct;
            }
            $approval->content = json_encode($content);
            $approval->user->save();
        } else if ($approval->status == 2) {
            $approval->user->status = 1;
            $approval->user->save();
        }
        $approval->admin_id = \Auth::id();
        $approval->save();
        return redirect(route('admin.approvals.closings'))->with('success', 'Approval has been updated');
    }

    public function kyc()
    {
        $approvals = Approval::where('type', 'KYC')->with('user')->orderBy('id', 'desc')->get()->toArray();
        return view('admin.approvals.kyc', compact('approvals'));
    }

    public function updatekyc(Request $request, $id)
    {
        $approval = Approval::with('ref')->findOrFail($id);
        $approval->status = intval($request->get('status', 0));
        if ($approval->status == 1) {
            $approval->user->kyc_status = 1;
            $approval->user->save();
        }
        $approval->admin_id = \Auth::id();
        $approval->save();
        return redirect(route('admin.approvals.kyc'))->with('success', 'Approval has been updated');
    }

    public function plans()
    {
        $plans = Plan::all();
        $approvals = Approval::where('type', 'UPPLAN')->with('user')->with('ref')->orderBy('id', 'desc')->get()->toArray();
        foreach ($approvals as $k => $a) {
            if ($a['content']) {
                $a['content'] = json_decode($a['content'], true);
                $a['content']['old_plan'] = "";
                if (isset($a['content']['old'])) {
                    foreach ($plans as $p) {
                        if ($p->id == $a['content']['old']) {
                            $a['content']['old_plan'] = $p->label;
                        }
                    }
                }
            }
            $approvals[$k] = $a;
        }
        return view('admin.approvals.plans', compact('approvals'));
    }

    public function updateplan(Request $request, $id)
    {
        $approval = Approval::with('ref')->findOrFail($id);
        $approval->status = intval($request->get('status', 0));
        if ($approval->status == 1) {
            $approval->user->plan_id = $approval->ref->id;
            $approval->user->status = 1;
            $approval->user->save();

            PlanReward::dispatch($approval->user, $approval->ref);
        }
        $approval->admin_id = \Auth::id();
        $approval->save();
        return redirect(route('admin.approvals.plans'))->with('success', 'Approval has been updated');
    }
}
