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
          <h3 class="profile-username text-center">{{$member['name']}}</h3>

          <p class="text-muted text-center">@include('partials.userstatus', ['status' => $member['status']])</p>

          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b><i class="fa fa-sitemap"></i> Downlines</b> <span
                class="pull-right">{{$member['downlines_qty']}}</span>
            </li>
            @foreach($member['wallets'] as $wallet)
            <li class="list-group-item">
              <b>{{$wallet['type']}} Wallet</b> <span class="pull-right">{{$wallet['balance']}}</span>
            </li>
            @endforeach
            <li class="list-group-item">
              <form action="{{ route('admin.members.resetsecuritypin', $member['id'])}}" method="post">
                @csrf
                <button class="btn btn-primary" type="submit">Reset Security Pin</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="nav-tabs-custom" id="tabs-member">
        <ul class="nav nav-tabs">
          <li><a href="#details" data-toggle="tab" aria-expanded="true">Details</a></li>
          <li><a href="#downlines" data-toggle="tab" aria-expanded="true">Downlines</a></li>
          @if($attachments)
          <li><a href="#payment" data-toggle="tab" aria-expanded="true">Payment</a></li>
          @endif
        </ul>
        <div class="tab-content">
          <div class="tab-pane" id="details">
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
              
          <form class="form-horizontal" method="POST" action="{{route('admin.members.update', ['id' => $member['id']])}}">
              @method('PATCH')
              @csrf
              <div class="form-group">
                <label class="col-md-3 control-label">Sponsor</label>

                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{$member['sponsor']['username']}}" disabled>
                </div>
              </div>
              <div class="form-group">
                <label for="input-plan" class="col-md-3 control-label">Investment Plan</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{$member['plan']['label']}}" disabled>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">User ID</label>

                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{$member['username']}}" disabled>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Full Name</label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="name" value="{{$member['name']}}">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Email</label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="email" value="{{$member['email']}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">Contact</label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="contact" value="{{$member['contact']}}">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Identification</label>

                <div class="col-md-8">
                  <div class="input-group">
                    <span class="input-group-addon"><select name="id_type">
                        @foreach($idTypes as $type)
                        <option value="{{$type}}" {{ $member['id_type'] == $type ? 'selected' : '' }}>{{$type}}</option>
                        @endforeach
                    </select></span>
                    <input type="text" class="form-control" name="id_number" value="{{$member['id_number']}}">
                  </div>
                </div>
              </div>
              <div class="box-footer">
                  <button type="submit" class="btn btn-primary pull-right">Update</button>
              </div>
            </form>
          </div>

          <div class="tab-pane" id="downlines">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Downlines</h3>
              </div>
              <div class="box-body">
                <table id="table-downlines" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Level</th>
                      <th>Plan</th>
                      <th>Downlines #</th>
                      <th>Joined</th>
                    </tr>
                  </thead>
                  <tbody style="display:none;">
                    @foreach($downlines as $downline)
                    <tr>
                      <td><a href="{{ route('admin.members.show',$downline['id'])}}" class="">{{$downline['name']}}</a>
                        @include('partials.userstatus', ['status' => $downline['status']])</td>
                      <td>{{$downline['level']}}</td>
                      <td>{{$downline['plan']['label']}}</td>
                      <td>{{$downline['downlines_qty']}}</td>
                      <td>{{Carbon\Carbon::parse($downline['created_at'])->format('Y-m-d')}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>

          @if($attachments)
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
          @endif

        </div>
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
    $('#table-downlines').DataTable();
    $('#table-downlines tbody').show();
  });
</script>
@endsection