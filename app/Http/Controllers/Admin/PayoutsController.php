<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\InterestPayouts;
use App\Payout;
use App\Rank;
use App\Setting;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payouts = Payout::orderBy('id', 'desc')->get()->toArray();
        return view('admin.payouts.index', compact('payouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payout = Payout::findOrFail($id)->toArray();
        $transactions = Transaction::with(['wallet', 'wallet.user'])->whereIn('type', ['INT', 'OVRINT', 'RNKINT', 'RBTINT'])->where('ref_id', $id)->where('ref_type', 'App\Payout')->orderBy('id', 'desc')->get()->toArray();
        $interests = [];
        $overrides = [];
        $rankings = [];
        $rebates = [];

        $ranks = [];
        $ranksRaw = Rank::all();
        foreach ($ranksRaw as $r) {
            $ranks[strval($r->id)] = $r;
        }

        foreach ($transactions as $t) {
            switch ($t['type']) {
                case 'INT':
                    $interests[] = $t;
                    break;
                case 'OVRINT':
                    $overrides[] = $t;
                    break;
                case 'RNKINT':
                    $t['extra'] = $ranks[explode('#', $t['extra'])[0]]['label'];
                    $rankings[] = $t;
                    break;
                case 'RBTINT':
                    $extra = explode(',', $t['extra']);
                    $t['extra'] = $extra;
                    $rebates[] = $t;
                    break;
            }
        }

        return view('admin.payouts.show', compact('payout', 'interests', 'overrides', 'rankings', 'rebates'));
    }

    public function create()
    {
        $members = User::active()->select(['id', 'username', 'has_grouprebate'])->where('role', 'member')->get()->toArray();
        return view('admin.payouts.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate(['rate' => 'required|numeric']);

        $payout = new Payout();
        $payout->rate = floatval($request->get('rate'));
        $payout->extra = Setting::where('option', 'felta_ratio')->first()->val;
        $payout->admin_id = \Auth::id();

        $payout->rebate_rate = floatval($request->get('rebate'));
        $rebateMembers = $request->get('rebatemembers');
        $payout->rebate_extra = implode(',', $rebateMembers ? $rebateMembers : []);

        $payout->save();

        $rebateMembersAry = explode(',', $payout->rebate_extra);
        User::whereIn('id', $rebateMembersAry)->update(['has_grouprebate' => true]);
        User::whereNotIn('id', $rebateMembersAry)->update(['has_grouprebate' => false]);

        InterestPayouts::dispatch($payout);

        return redirect(route('admin.payouts.index'))->with('success', 'Payout has been initiated');
    }

}
