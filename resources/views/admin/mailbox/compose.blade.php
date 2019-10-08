@extends('layouts.admin')

@section('htmlheader_title')
Compose
@endsection

@section('contentheader_title')
Compose
@endsection


@section('main-content')
<div class="row">
  <div class="col-md-3">
    @include('admin.mailbox.sidebar', ['unreadCount' => $unreadCount])
  </div>

  <div class="col-md-9">
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    <br />
    @endif
    <form method="POST" action="{{route('admin.mailbox.send')}}">
      @csrf
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">
            @if($recipient == '*')
            Componse New Announcement
            @else
            Compose New Message
            @endif
          </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          
          <div class="form-group">
              @if($recipient != '*')
            <input name="recipient" class="form-control" placeholder="Recipient username" value="{{$recipient}}">
            @else
            <input type="hidden" name="recipient" value="sys" />
            @endif
          </div>
          <div class="form-group">
            <input name="subject" class="form-control" placeholder="Subject" value="{{$subject}}">
          </div>
          <div class="form-group">
            <textarea name="body" id="compose-textarea" class="form-control" style="height: 300px"
              placeholder="Message">{{old('body')}}</textarea>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <div class="pull-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
          </div>
          <a href="{{route('admin.mailbox.inbox')}}" class="btn btn-default"><i class="fa fa-times"></i> Discard</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('footerscripts')
@endsection