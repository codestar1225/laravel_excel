<?php

namespace App\Http\Controllers\Member;

use App\Approval;
use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Rules\UserEmailCount;
use App\User;
use Illuminate\Http\Request;

class DownlinesController extends Controller
{
    protected function createTree(&$list, $parent)
    {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l['uid']])) {
                $l['children'] = $this->createTree($list, $list[$l['uid']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    public function hierarchy()
    {
        $rootTree = [];
        $user = User::where('id', \Auth::id())->with('plan')->first();
        $validNames = [$user->username];
        $membersRaw = User::valid()->where('parents', 'like', '%,' . $user->id . ',%')->with('plan')->orderBy('id', 'asc')->get();
        $members = [];
        foreach ($membersRaw as $m) {
            $members[] = ['uid' => $m->id, 'id' => 'node-' . $m->username, 'username' => $m->username, 'body' => $m->name . '<br>' . $m->plan->label, 'sponsor_id' => $m->sponsor_id];
            $validNames[] = $m->username;
        }
        $members = array_merge([['uid' => $user->id, 'id' => 'node-' . $user->username, 'username' => $user->username, 'body' => $user->name . '<br>' . ($user->plan ? $user->plan->label : ''), 'sponsor_id' => 0]], $members);

        $links = array();
        foreach ($members as $m) {
            $links[$m['sponsor_id']][] = $m;
        }
        $tree = $this->createTree($links, array($members[0]));

        $rootTree = json_encode($tree);
        $rootTree = trim(trim($rootTree, '['), ']');
        $username = $user->username;

        $validNames = json_encode($validNames);
        return view('member.downlines.chart', compact('rootTree', 'username', 'validNames'));
    }

    public function tabular()
    {
        $rootTree = [];
        $user = User::where('id', \Auth::id())->with('plan')->first();
        $validNames = [$user->username];
        $membersRaw = User::valid()->where('parents', 'like', '%,' . $user->id . ',%')->with('plan')->orderBy('id', 'asc')->get();
        $members = [];
        foreach ($membersRaw as $m) {
            $members[] = ['uid' => $m->id, 'id' => 'node-' . $m->username, 'text' => $m->username, 'sponsor_id' => $m->sponsor_id];
            $validNames[] = $m->username;
        }
        $members = array_merge([['uid' => $user->id, 'id' => 'node-' . $user->username, 'text' => $user->username, 'sponsor_id' => 0]], $members);

        $links = array();
        foreach ($members as $m) {
            $links[$m['sponsor_id']][] = $m;
        }
        $tree = $this->createTree($links, array($members[0]));

        $rootTree = json_encode($tree);
        $rootTree = trim(trim($rootTree, '['), ']');
        $username = $user->username;

        $validNames = json_encode($validNames);
        return view('member.downlines.tabular', compact('rootTree', 'username', 'validNames'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function list() {
        $user = \Auth::user();

        $members = User::where('parents', 'like', '%,' . $user->id . ',%')->with('plan')->orderBy('id', 'desc')->get();
        return view('member.downlines.list', compact('members', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plans = Plan::all();
        $idTypes = ['IC', 'Passport'];
        return view('member.downlines.create', compact('plans', 'idTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());
        $user = \Auth::user();
        $parents = rtrim($user->parents, ',') . ',' . $user->id . ',';
        $password = str_random(12);
        $member = new User([
            'plan_id' => $request->get('plan_id'),
            'sponsor_id' => $user->id,
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => bcrypt($password),
            'name' => $request->get('name'),
            'contact' => $request->get('contact'),
            'id_type' => $request->get('id_type'),
            'id_number' => $request->get('id_number'),
            'parents' => $parents,
        ]);
        $member->save();
        event(new UserCreated($member, $password));
        return redirect(route('member.downlines.show', ['id' => $member->id]))->with('success', 'Member has been created');
    }

    public function rules()
    {
        return [
            'plan_id' => 'required|numeric',
            'username' => 'required|unique:users,username|alpha_num',
            'email' => ['required',
                'email',
                'regex:/(.*)@(.*)\.(.*)/i',
                new UserEmailCount],
            'name' => 'required',
            'id_type' => 'required',
            'id_number' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The user id field is required.',
            'name.required' => 'The full name field is required.',
            'plan_id.required' => "The plan field is required.",
            'id_number.required' => "The identification field is required.",
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \Auth::user();
        $member = User::select("*")->where('id', $id)->where('parents', 'like', '%' . $user->id . '%')
            ->with('plan')->with('wallets')->with('sponsor')->withDownlinesQty()->firstOrFail();
        $paymentAddress = "";
        if ($member->status == 0) {
            $paymentAddress = \App\Setting::where('option', 'company_eth_wallet')->first()->val;
        }

        $attachments = [
            'PAYMENT_FILE' => "",
            'PAYMENT_TXID' => "",
        ];
        $approval = Approval::where('type', 'SPONSOR')->where('ref_id', $id)->where('ref_type', 'App\User')->first();
        if ($approval) {
            $attachments = json_decode($approval->content, true);
        }
        return view('member.downlines.profile', compact('member', 'paymentAddress', 'attachments'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sponsorID = \Auth::id();
        $member = User::where('id', $id)->where('sponsor_id', $sponsorID)->firstOrFail();
        $member->delete();

        $approval = Approval::where('type', 'SPONSOR')->where('ref_id', $id)->where('ref_type', 'App\User')->where('user_id', $sponsorID)->first();
        if ($approval) {
            $approval->delete();
        }
        return redirect(route('member.downlines.list'))->with('success', 'Member has been deleted');
    }

    public function payment(Request $request, $id)
    {
        $user = \Auth::user();
        $member = User::where('id', $id)->where('sponsor_id', $user->id)->firstOrFail();

        $attachments = [
            'PAYMENT_FILE' => "",
            'PAYMENT_TXID' => "",
        ];
        $approval = Approval::where('type', 'SPONSOR')->where('ref_id', $member->id)->where('ref_type', 'App\User')->firstOrFail();
        if ($approval->content) {
            $attachments = json_decode($approval->content, true);
        }

        if ($attachments['PAYMENT_FILE']) {
            \Storage::delete($attachments['PAYMENT_FILE']);
        }

        $file = $request->file('txfile');
        if ($file) {
            $path = $request->file('txfile')->store('attachments');
            $attachments['PAYMENT_FILE'] = $path;
        }

        $attachments['PAYMENT_TXID'] = $request->get('txid', '');
        $approval->content = json_encode($attachments);
        $approval->save();
        return redirect(route('member.downlines.show', $id))->with('success', 'Proof of payment submitted');
    }
}
