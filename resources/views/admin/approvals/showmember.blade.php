@extends('layouts.admin')

@section('htmlheader_title')
Member Profile
@endsection

@section('contentheader_title')
Member Profile
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
  <div class="row">
    <div class="col-md-4">
      <div class="box box-primary">
        <div class="box-body box-profile">
          <h3 class="profile-username text-center">{{$member->name}}</h3>

          <p class="text-muted text-center">@include('partials.userstatus', ['status' => $member->status])</p>

          <ul class="list-group list-group-unbordered">
            @foreach($member->wallets as $wallet)
            <li class="list-group-item">
              <b>{{$wallet->type}} Wallet</b> <span class="pull-right">{{$wallet->balance}}</span>
            </li>
            @endforeach
            @if($approval['status'] == 0)
            <li class="list-group-item">
              <div class="row">
                <div class="col-md-6">
                  <form action="{{ route('admin.approvals.updatemember', $approval['id'])}}" method="post">
                    @csrf
                    @method("PATCH")
                    <input name="status" type="hidden" value="1" />
                    <button class="btn btn-block btn-success" type="submit">Approve</button>
                  </form>
                </div>
                <div class="col-md-6">
                  <form action="{{ route('admin.approvals.updatemember', $approval['id'])}}" method="post">
                    @csrf
                    @method("PATCH")
                    <input name="status" type="hidden" value="2" />
                    <button class="btn btn-block btn-danger" type="submit">Reject</button>
                  </form>
                </div>
              </div>
            </li>
            @endif
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="nav-tabs-custom" id="tabs-member">
        <ul class="nav nav-tabs">
          <li><a href="#payment" data-toggle="tab" aria-expanded="true">Payment</a></li>
          <li><a href="#details" data-toggle="tab" aria-expanded="true">Details</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane" id="payment">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Proof of payment</h3>
              </div>
              <form class="form-horizontal">
                <div class="box-body attachments">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Transaction ID</label>
                    <div class="col-md-9">
                      @if(isset($attachments['PAYMENT_TXID']))
                      <input type="text" class="form-control" name="txid" id="txid"
                        value="{{ $attachments['PAYMENT_TXID']}}" autocomplete="off" disabled>
                      @else
                      <input type="text" class="form-control" name="txid" id="txid" autocomplete="off">
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Payment Image</label>
                    <div class="col-md-9">
                      @if($attachments['PAYMENT_FILE'])
                      <a href="{{url($attachments["PAYMENT_FILE"])}}" target="_blank"><img
                          src="{{url($attachments["PAYMENT_FILE"])}}" class="attachment-img" /></a>
                      @endif
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="tab-pane" id="details">
            <form class="form-horizontal">
              <div class="form-group">
                <label class="col-md-3 control-label">Sponsor</label>

                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{$member->sponsor->name}}" disabled>
                </div>
              </div>
              <div class="form-group">
                <label for="input-plan" class="col-md-3 control-label">Investment Plan</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{$member->plan->label}}" disabled>
                </div>
              </div>
              <div class="form-group">
                  <label class="col-md-3 control-label">User ID</label>
  
                  <div class="col-md-8">
                    <input type="text" class="form-control" value="{{$member->username}}" disabled>
                  </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Full Name</label>
    
                    <div class="col-md-8">
                      <input type="text" class="form-control" value="{{$member->name}}" disabled>
                    </div>
                  </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Email</label>

                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{$member->email}}" disabled>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-md-3 control-label">Contact</label>

                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{$member->contact}}" disabled>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Identification</label>

                <div class="col-md-8">
                  <div class="input-group">
                    <span class="input-group-addon">{{$member->id_type}}</span>
                    <input type="text" class="form-control" value="{{$member->id_number}}" disabled>
                  </div>

                </div>
              </div>
            </form>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('footerscripts')
<script>
  $(document).ready(function(){
    $('#tabs-member .nav-tabs li:first').addClass("active");
    $('#tabs-member .tab-content div:first').addClass("active");
  });
</script>
@endsection