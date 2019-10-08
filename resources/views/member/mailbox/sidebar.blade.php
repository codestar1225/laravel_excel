<a href="{{route('member.mailbox.compose')}}" class="btn btn-primary btn-block margin-bottom">Compose</a>
<br />
<div class="box box-solid">
    <div class="box-header with-border">
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="{{ request()->is('member/mailbox') ? 'active' : '' }}"><a href="{{route('member.mailbox.inbox')}}"><i class="fa fa-inbox"></i> Inbox
                    <span class="label label-primary pull-right">{{$unreadCount}}</span></a></li>
            <li class="{{ request()->is('member/mailbox/outbox') ? 'active' : '' }}"><a href="{{route('member.mailbox.outbox')}}"><i class="fa fa-envelope-o"></i> Sent</a></li>
            <li class="{{ request()->is('member/mailbox/announcements') ? 'active' : '' }}"><a href="{{route('member.mailbox.announcements')}}"><i class="fa fa-comments"></i> Announcements</a></li>
        </ul>
    </div>
</div>