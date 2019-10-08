@extends('layouts.member')

@section('htmlheader_title')
{{$title}}
@endsection

@section('contentheader_title')
{{$title}}
@endsection


@section('main-content')
<div class="row">
    <div class="col-md-3">
        @include('member.mailbox.sidebar', ['unreadCount' => $unreadCount])
    </div>
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{$title}}</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="table-threads" class="table table-hover table-striped">
                        <thead style="display:none">
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody style="display:none;">
                            @foreach($threads as $thread)
                            <tr class="{{ ($isAnnouncement || $thread['pivot']['seen_at']) ? '' : 'mail-unread'}}">
                                <td class="mailbox-subject"><a
                                        href="{{route('member.mailbox.show', $thread['id'])}}">{{$thread['subject']}}</a>
                                </td>
                                <td class="mailbox-date">
                                    {{Carbon\Carbon::parse($thread['created_at'])->format('Y-m-d H:i:s')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerscripts')
<script>
    $('#table-threads').DataTable({
"bSort" : false,
} );
    $('#table-threads tbody').show();
</script>
@endsection