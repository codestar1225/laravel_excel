<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Liliom\Inbox\Models\Participant;
use Liliom\Inbox\Models\Thread;

class MailboxController extends Controller
{
    public function inbox()
    {
        $user = \Auth::user();
        $threads = $user->received()->where('threads.user_id', '<>', $user->id)->with('user')->get()->toArray();
        $unreadCount = $user->unread->count();
        $title = "Inbox";
        $isAnnouncement = false;
        return view('admin.mailbox.list', compact('threads', 'unreadCount', 'title', 'isAnnouncement'));
    }

    public function show($id)
    {
        $thread = Thread::with('participants')->findOrFail($id);
        $userID = \Auth::id();
        $isAnnouncement = false;
        $target = null;
        foreach($thread->participants as $p){
            if($p->id == 2){
                $isAnnouncement = true;
                $found = true;
            }
            else if($p->id == $userID){
                $found = true;
            }
            else{
                $target = $p->toArray();
            }
        }
        if(!$found){
            \App::abort(404);
        }
        $participant = Participant::where('thread_id', $id)->with('thread')->where('user_id', \Auth::id())->first();
        if($participant){
            $participant->seen_at = Carbon::now();
            $participant->save();
        }
        else if(!$isAnnouncement){
            \App::abort(404);
        }
        $message = $thread->lastMessage()->toArray();
        $unreadCount = \Auth::user()->unread->count();
        return view('admin.mailbox.show', compact('unreadCount', 'target', 'message', 'thread', 'isAnnouncement'));
    }

    public function outbox()
    {
        $user = \Auth::user();
        $threadsRaw = $user->sent()->with('user')->with('participants')->get()->toArray();
        $threads = [];
        foreach ($threadsRaw as $t) {
            $target = null;
            foreach ($t['participants'] as $p) {
                if ($p['id'] != $user->id) {
                    $target = $p;
                }
            }
            if ($target['username'] == 'sys') {
                continue;
            }
            $t['user'] = $target;
            $threads[] = $t;
        }

        $unreadCount = $user->unread->count();
        $title = "Sent";
        $isAnnouncement = false;
        return view('admin.mailbox.list', compact('threads', 'unreadCount', 'title', 'isAnnouncement'));
    }

    public function announcements()
    {
        $threads = array();
        $participants = Participant::inbox(2)->with('thread')->latest()->get();
        foreach ($participants as $p) {
            $t = $p->thread->toArray();
            $t['pivot'] = ['seen_at' => Carbon::now()];
            $threads[] = $t;
        }
        $unreadCount = \Auth::user()->unread->count();
        $title = "Announcements";
        $isAnnouncement = true;
        return view('admin.mailbox.list', compact('threads', 'unreadCount', 'title', 'isAnnouncement'));
    }

    public function delete($id)
    {
        $userID = \Auth::id();
        $participants = Participant::with('user')->where('thread_id', $id)->get();
        $isAnnouncement = false;
        foreach ($participants as $p) {
            if ($p->user->username == 'sys') {
                $isAnnouncement = true;
            } 
            $p->delete();
        }
        if ($isAnnouncement) {
            Thread::destroy($id);
        }
        return redirect(route('admin.mailbox.' . ($isAnnouncement ? 'announcements' : 'inbox')))->with('success', ($isAnnouncement ? 'Announcement' : 'Mail') . ' has been deleted.');
    }

    public function compose(Request $request)
    {
        $unreadCount = \Auth::user()->unread->count();
        $subject = $request->subject;
        $recipient = $request->recipient;
        return view('admin.mailbox.compose', compact('unreadCount', 'subject', 'recipient'));
    }

    public function send(Request $request)
    {
        $request->validate(['subject' => 'required', 'recipient' => 'required', 'body' => 'required']);
        $recipient = User::where('username', 'like', $request->recipient)->first();
        if (!$recipient) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'recipient' => ['Unknown recipient'],
            ]);
            throw $error;
        }
        $isAnnouncement = $recipient->username == 'sys';
        $message = $isAnnouncement ? 'Announcement has been published.' : 'Mail has been sent.';
        $thread = \Auth::user()->subject($request->subject)
            ->writes(nl2br(strip_tags($request->body)))
            ->to($recipient->id)
            ->send();
        return redirect(route('admin.mailbox.' . ($isAnnouncement ? 'announcements' : 'outbox')))->with('success', $message);
    }
}
