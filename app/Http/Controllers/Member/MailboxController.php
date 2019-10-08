<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Liliom\Inbox\Models\Thread;
use Liliom\Inbox\Models\Participant;
use Liliom\Inbox\Models\Message;
use Carbon\Carbon;
use App\User;

class MailboxController extends Controller
{
    public function inbox() {
        $user = \Auth::user();
        $threads = $user->received()->where('threads.user_id', '<>', $user->id)->with('user')->get()->toArray();
        $unreadCount = $user->unread->count();
        $title = "Inbox";
        $isAnnouncement = true;
        return view('member.mailbox.list', compact('threads', 'unreadCount', 'title', 'isAnnouncement'));
    }

    public function show($id){
        $thread = Thread::with('participants')->findOrFail($id);
        $found = false;
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

        return view('member.mailbox.show', compact('unreadCount', 'target', 'message', 'thread', 'isAnnouncement'));
    }

    public function outbox(){
        $user = \Auth::user();
        $threads = $user->sent()->with('user')->get()->toArray();
        $unreadCount = $user->unread->count();
        $title = "Sent";
        $isAnnouncement = false;
        return view('member.mailbox.list', compact('threads', 'unreadCount', 'title', 'isAnnouncement'));
    }

    public function delete($id){
        $participant = Participant::where('thread_id', $id)->where('user_id', \Auth::id())->firstOrFail();
        $participant->delete();
        return redirect(route('member.mailbox.inbox'))->with('success', 'Mail has been deleted.');
    }

    public function compose(Request $request){
        $unreadCount = \Auth::user()->unread->count();
        $subject = $request->get('subject', '');
        return view('member.mailbox.compose', compact('unreadCount', 'subject'));
    }

    public function send(Request $request){
        $request->validate(['subject' => 'required', 'body' => 'required']);
        $thread = \Auth::user()->subject($request->subject)
            ->writes(nl2br(strip_tags($request->body)))
            ->to(1)
            ->send();
        return redirect(route('member.mailbox.outbox'))->with('success', 'Mail has been sent.');
    }

    public function announcements()
    {
        $threads = array();
        $participants = Participant::inbox(2)->with('thread')->latest()->get();
        foreach($participants as $p){
                $threads[] = $p->thread->toArray();
        }
        $unreadCount = \Auth::user()->unread->count();
        $title = "Announcements";
        $isAnnouncement = true;
        return view('member.mailbox.list', compact('threads', 'unreadCount', 'title', 'isAnnouncement'));
    }
}
