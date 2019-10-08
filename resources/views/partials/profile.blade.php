@section('htmlheader_title')
Profile
@endsection

@section('contentheader_title')
Profile
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <h3 class="profile-username text-center">{{$member['name']}} <span>@include('partials.userstatus',
                            ['status' => $member['status']])</span></h3>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Investment Plan</b> <span class="pull-right">{{$member['plan']['label']}}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fa fa-sitemap"></i> Downlines</b> <span
                                class="pull-right">{{$member['downlines_qty']}}</span>
                        </li>
                        @foreach($member['wallets'] as $wallet)
                        <li class="list-group-item">
                            <b>{{$wallet['type']}} Wallet</b> <span class="pull-right">{{$wallet['balance']}}</span>
                        </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="nav-tabs-custom" id="tabs-member">
                <ul class="nav nav-tabs">
                    @if($isOwn && $member['status'] == 0 && $member['role'] == 'member')
                    <li><a href="#payment" data-toggle="tab" aria-expanded="true">Payment</a></li>
                    @endif
                    <li><a href="#details" data-toggle="tab" aria-expanded="true">Details</a></li>
                    <li><a href="#passwords" data-toggle="tab" aria-expanded="true">Passwords</a></li>
                    <li><a href="#securitypin" data-toggle="tab" aria-expanded="true">Security Pin</a></li>
                    @if($isOwn && in_array($member['status'], [1,3]))
                    <li><a href="#closing" data-toggle="tab" aria-expanded="true">Close Account</a></li>
                    @endif
                    @if($isOwn)
                    <li><a href="#kyc" data-toggle="tab" aria-expanded="true">KYC</a></li>
                    @endif
                </ul>
                <div class="tab-content">
                    @if($isOwn && $member['status'] == 0 && $member['role'] == 'member')
                    <div class="tab-pane" id="payment">
                        <div class="alert alert-warning alert-block">
                            <p>Please make payment to continue with activation. </p>
                            <p>ETH payment address: <strong>0x8b9751Ba6442842d4e977eB0Ed00186649E56F51</strong></p>
                            @isset($member['plan'])
                            <p>Investment Plan: <strong>{{$member['plan']['label']}}</strong></p>
                            @endisset
                        </div>
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Upload proof of payment</h3>
                            </div>
                            <form class="form-horizontal" method="POST" enctype="multipart/form-data"
                                action="{{route('profile.payment')}}">
                                @csrf
                                <div class="box-body attachments">
                                    <div class="form-group">
                                        <label for="input-txid" class="col-md-3 control-label">Transaction ID</label>
                                        <div class="col-md-9">
                                            @if(isset($attachments['PAYMENT_TXID']))
                                            <input type="text" class="form-control" name="txid" id="txid"
                                                value="{{ $attachments['PAYMENT_TXID']}}" autocomplete="off">
                                            @else
                                            <input type="text" class="form-control" name="txid" id="txid"
                                                autocomplete="off">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-txfile" class="col-md-3 control-label">Payment Image</label>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control" name="txfile" id="txfile">
                                            @if(isset($attachments['PAYMENT_FILE']) && $attachments['PAYMENT_FILE'])
                                            <a href="{{url($attachments["PAYMENT_FILE"])}}" target="_blank"><img
                                                    src="{{url($attachments["PAYMENT_FILE"])}}"
                                                    class="attachment-img" /></a>
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
                                    @if($member['sponsor'])
                                    <input type="text" class="form-control" value="{{$member['sponsor']['name']}}"
                                        disabled>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-plan" class="col-md-3 control-label">Investment Plan</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="{{$member['plan']['label']}}"
                                        disabled>
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
                                    <input type="text" class="form-control" value="{{$member['name']}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="{{$member['email']}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Contact</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="{{$member['contact']}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Identification</label>

                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">{{$member['id_type']}}</span>
                                        <input type="text" class="form-control" value="{{$member['id_number']}}"
                                            disabled>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="passwords">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">

                                <form class="form-horizontal" action="{{route('profile.index').'#passwords'}}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="password" />
                                    <div class="form-group">
                                        <label for="input-cpassword" class="col-md-4 control-label">Current
                                            Password</label>
                                        <div class="col-md-8">
                                            <input type="password" name="cpassword" class="form-control"
                                                id="input-cpassword" placeholder="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="input-password" class="col-md-4 control-label">Password</label>
                                        <div class="col-md-8">
                                            <input type="password" name="password" class="form-control"
                                                id="input-password" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-password_confirmation" class="col-md-4 control-label">Confirm
                                            Password</label>
                                        <div class="col-md-8">
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="input-password_confirmation" placeholder="">
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="securitypin">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">

                                <form class="form-horizontal" action="{{route('profile.index').'#securitypin'}}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="security_pin" />
                                    <div class="form-group">
                                        <label for="input-cpassword" class="col-md-4 control-label">Current
                                            Security Pin</label>
                                        <div class="col-md-8">
                                            <input type="password" name="cpassword" class="form-control"
                                                id="input-cpassword" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-password" class="col-md-4 control-label">Security Pin</label>
                                        <div class="col-md-8">
                                            <input type="password" name="password" class="form-control"
                                                id="input-password" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-password_confirmation" class="col-md-4 control-label">Confirm
                                            Security Pin</label>
                                        <div class="col-md-8">
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="input-password_confirmation" placeholder="">
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($isOwn && in_array($member['status'], [1,3]))
                    <div class="tab-pane" id="closing">
                        <div class="box">
                            <div class="box-body">
                                <div class="alert alert-info">
                                    Closing request normally takes 1-5 business days to process. You will be able to
                                    cancel request within this period.
                                    <p>Capital Withdrawal:</p>
                                    <p>(a) below 1 month : 20% withdrawal fee</p>
                                    <p>(b) within 1st and 2nd month : 10% withdrawal fee</p>
                                    <p>(c) more than 2 month: 0% withdrawal fee</p>
                                    </br>
                                    <p>Remarks:</p>
                                    <p>Please withdraw all the balance before you close the account. All the balance will not be refunded after the account is closed.</p>
                                </div>
                                <div class="row">
                                    @if($member['status'] == 1)
                                    <div class="col-md-6 col-md-offset-3">
                                        <form method="POST" action="{{route('member.closing.close')}}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="input-addr" class="control-label">Withdraw balances to
                                                        ETH Wallet Address</label>
                                                    <div>
                                                        <input class="form-control" autocomplete="off" id="input-addr"
                                                            type="text" name="address" />
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" href="{{route('member.closing.close')}}"
                                                class="btn btn-block btn-primary">Proceed Closing</button>
                                        </form>
                                    </div>
                                    @endif
                                    @if($member['status'] == 3)
                                    <div class="col-md-4 col-md-offset-4">
                                        <a href="{{route('member.closing.cancel')}}"
                                            class="btn btn-block btn-primary">Cancel Request</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($isOwn)
                    <div class="tab-pane" id="kyc">
                        @if($member['kyc_status'] == 1)
                        <div class="alert alert-success">
                            Your account as been verified.
                        </div>
                        @else
                        <form class="form-horizontal" action="{{route('profile.kyc')}}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="box-body attachments">
                                @isset($member['kyccontent'])
                                @if($member['kycstatus'] == 2)
                                <div class="alert alert-error">
                                    Your verification has been rejected, please upload a clearer proof of ID.
                                </div>
                                @else
                                <div class="alert alert-info">
                                    Your account is under review.
                                </div>
                                @endif
                                @endisset
                                <div class="form-group">
                                    <label for="input-file" class="col-md-4 control-label">Upload photo of your
                                        ID</label>
                                    <div class="col-md-8">
                                        <input type="file" class="form-control" name="file" id="input-file">
                                        @isset($member['kyccontent'])
                                        <a href="{{ url($member['kyccontent'])}}" target="_blank"><img
                                                src="{{ url($member['kyccontent'])}}" class="attachment-img" /></a>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right">Submit</button>
                            </div>
                        </form>
                        @endif
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
        var link = $('#tabs-member .nav-tabs li:first a');
        var hash = window.location.hash.substr(1);
        if(hash){
            var l = $('#tabs-member .nav-tabs li a[href="#'+hash+'"]');
            if(l.length > 0){
                link = l;
            }
        }
        link.trigger('click');
        history.replaceState(null, null, ' ');
  });
</script>
@endsection