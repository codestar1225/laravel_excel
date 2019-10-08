@extends('layouts.admin')

@section('htmlheader_title')
Read Mail
@endsection

@section('contentheader_title')
Read Mail
@endsection


@section('main-content')
<div class="row">
    <div class="col-md-3">
        @include('admin.mailbox.sidebar', ['unreadCount' => $unreadCount])
    </div>
    <div class="col-md-9">
            <div class="box box-primary">
              <div class="box-header with-border">
                    <a class="btn btn-default" href="{{URL::previous()}}">Back</a>
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <div class="mailbox-read-info">
                  <h3>{{$thread['subject']}}</h3>
                  
                  <h5>
                      @if(!$isAnnouncement)
                      {{$target['id'] == $message['user_id'] ? 'From' : 'To'}}: {{$target['name']}}
                      @endif
                      &nbsp;
                    <span class="mailbox-read-time pull-right">{{Carbon\Carbon::parse($message['created_at'])->format('Y-m-d H:i:s')}}</span></h5>
                    
                </div>
                <div class="mailbox-read-message">
                  {!! $message['body'] !!}
                </div>
              </div>

              <!-- /.box-footer -->
              <div class="box-footer">
                @if(!$isAnnouncement)
                <div class="pull-right">
                    <a href="{{route('admin.mailbox.compose', ['subject' => 'Re: '.$thread['subject'], 'recipient' => $target['username']])}}" class="btn btn-default"><i class="fa fa-reply"></i> Reply</a>
                </div>
                @endif
            <a href="{{route('admin.mailbox.delete', $thread['id'])}}" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</a>
              </div>
              <!-- /.box-footer -->
            </div>
            <!-- /. box -->
          </div>
        </div>
</div>
@endsection