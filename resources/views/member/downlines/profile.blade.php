@extends('layouts.member')

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
            <li class="list-group-item">
              <b><i class="fa fa-sitemap"></i> Downlines</b> <span class="pull-right">{{$member->downlines_qty}}</span>
            </li>
            @foreach($member->wallets as $wallet)
            <li class="list-group-item">
              <b>{{$wallet->type}} Wallet</b> <span class="pull-right">{{$wallet->balance}}</span>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="nav-tabs-custom" id="tabs-member">
        <ul class="nav nav-tabs">
          @if($member->status == 0 && $member->sponsor_id == $user->id)
          <li><a href="#payment" data-toggle="tab" aria-expanded="true">Payment</a></li>
          @endif
          <li><a href="#details" data-toggle="tab" aria-expanded="true">Details</a></li>
        </ul>
        <div class="tab-content">
          @if($member->status == 0 && $member->sponsor_id == $user->id)
          <div class="tab-pane" id="payment">
            <div class="alert alert-warning alert-block">
              <p>Please make payment to continue with activation. </p>
              <p>ETH payment address: <strong>{{$paymentAddress}}</strong></p>
              <p>Investment Plan: <strong>{{$member->plan->label}}</strong></p>
            </div>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Upload proof of payment</h3>
              </div>
              <form class="form-horizontal" method="POST" enctype="multipart/form-data"
                action="{{route('member.downlines.payment', $member->id)}}">
                @csrf
                <div class="box-body attachments">
                  <div class="form-group">
                    <label for="input-txid" class="col-md-3 control-label">Transaction ID</label>
                    <div class="col-md-9">
                      @if(isset($attachments['PAYMENT_TXID']))
                      <input type="text" class="form-control" name="txid" id="txid"
                        value="{{ $attachments['PAYMENT_TXID']}}" autocomplete="off">
                      @else
                      <input type="text" class="form-control" name="txid" id="txid" autocomplete="off">
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="input-txfile" class="col-md-3 control-label">Payment Image</label>
                    <div class="col-md-9">
                      <input type="file" class="form-control" name="txfile" id="txfile">
                      @if($attachments['PAYMENT_FILE'])
                        <a href="{{url($attachments["PAYMENT_FILE"])}}" target="_blank"><img src="{{url($attachments["PAYMENT_FILE"])}}" class="attachment-img" /></a>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
              </form>
            </div>
          </div>
          @endif
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