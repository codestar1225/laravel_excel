<?php

namespace App\Http\Controllers\Admin;

use App\Approval;
use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Plan;
use App\User;
use Illuminate\Http\Request;
use App\Mail\ResetSecurityPinMail;
use Illuminate\Support\Facades\Mail;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function list() {
        $members = User::with(['plan', 'sponsor'])->where('role', 'member')->get()->toArray();
        return view('admin.members.list', compact('members'));
    }

    public function resetSecurityPin($id){
        $user = User::findOrFail($id);
        $user->withdrawkey = strtoupper(str_random(6));
        $user->save();
        Mail::to($user->email)->send((new ResetSecurityPinMail($user))->subject('New Security Pin'));
        return redirect()->back()->with('success', 'Security pin has been resetted');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = User::select("*")->where('id', $id)->with('plan')->with('wallets')->with('sponsor')->withDownlinesQty()->firstOrFail()->toArray();

        $attachments = null;
        $approval = Approval::where('type', 'SPONSOR')->where('user_id', $id)->first();
        if ($approval) {
            $attachments = json_decode($approval->content, true);
        }

        $downlines = [];
        $downlinesraw = User::select("*")->with('plan')->where('parents', 'like', '%' . $id . '%')->withDownlinesQty()->get();
        foreach($downlinesraw as $d){
            $data = $d->toArray();
            $data['level'] = $d->getLevelFor($id);
            $downlines[] = $data;
        }
        $idTypes = ['IC', 'Passport'];
        return view('admin.members.profile', compact('member', 'attachments', 'downlines', 'idTypes'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'email' => ['required',
                'email',
                'regex:/(.*)@(.*)\.(.*)/i'],
            'name' => 'required',
            'contact' => 'required',
            'id_type' => 'required',
            'id_number' => 'required',
        ]);
        $member = User::findOrFail($id);
        $member->name = $request->get('name');
        $member->email = $request->get('email');
        $member->id_type = $request->get('id_type');
        $member->id_number = $request->get('id_number');
        $member->contact = $request->get('contact');
        $member->save();
        return redirect(route('admin.members.show', ['id' => $id]))->with('success', 'Member has been updated');
    }

    protected function createTree(&$list, $parent){
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['uid']])){
                $l['children'] = $this->createTree($list, $list[$l['uid']]);
            }
            $tree[] = $l;
        } 
        return $tree;
    }

    public function hierarchy(){
        $rootTree = [];
        $user = User::where('id', 3)->with('plan')->first();
        $validNames = [$user->username];
        $membersRaw = User::valid()->where('parents', 'like', '%,' . $user->id. ',%')->with('plan')->orderBy('id', 'asc')->get();
        $members = [];
        foreach($membersRaw as $m){
            $members[] = ['uid' => $m->id, 'id' => 'node-'.$m->username, 'username' => $m->username, 'body' => $m->name. '<br>'. $m->plan->label, 'sponsor_id' => $m->sponsor_id];
            $validNames[] = $m->username;
        }
        $members = array_merge([['uid' => $user->id, 'id' => 'node-'.$user->username, 'username' => $user->username, 'body' => $user->name . '<br>' . ($user->plan ? $user->plan->label : ''), 'sponsor_id' => 0]], $members);

        $links = array();
        foreach ($members as $m){
            $links[$m['sponsor_id']][] = $m;
        }
        $tree = $this->createTree($links, array($members[0]));

        $rootTree = json_encode($tree);
        $rootTree = trim(trim($rootTree, '['), ']');
        $username = $user->username;

        $validNames = json_encode($validNames);
        return view('admin.members.chart', compact('rootTree', 'username', 'validNames'));
    }

    public function tabular(){
        $rootTree = [];
        $user = User::where('id', 3)->with('plan')->first();
        $validNames = [$user->username];
        $membersRaw = User::valid()->where('parents', 'like', '%,' . $user->id. ',%')->with('plan')->orderBy('id', 'asc')->get();
        $members = [];
        foreach($membersRaw as $m){
            $members[] = ['uid' => $m->id, 'id' => 'node-'.$m->username, 'text' => $m->username, 'sponsor_id' => $m->sponsor_id];
            $validNames[] = $m->username;
        }
        $members = array_merge([['uid' => $user->id, 'id' => 'node-'.$user->username, 'text' => $user->username, 'sponsor_id' => 0]], $members);

        $links = array();
        foreach ($members as $m){
            $links[$m['sponsor_id']][] = $m;
        }
        $tree = $this->createTree($links, array($members[0]));

        $rootTree = json_encode($tree);
        $rootTree = trim(trim($rootTree, '['), ']');
        $username = $user->username;

        $validNames = json_encode($validNames);
        return view('admin.members.tabular', compact('rootTree', 'username', 'validNames'));
    }
}
