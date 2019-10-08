@extends('layouts.member')

@section('htmlheader_title')
Withdrawals
@endsection

@section('contentheader_title')
Withdrawals
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">My Wallet</h3>
                </div>
                <div class="box-body">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>ETH</b> <span class="pull-right">{{$wallet['balance']}}</span>
                        </li>
                        <li class="list-group-item">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if($kycStatus == "1")
                            <form method="POST" action="{{route('member.wallets.withdraw')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="input-amt" class="control-label">Withdraw amount</label>
                                    <div>
                                        <input class="form-control" step="any" autocomplete="off" id="input-amt" type="number"
                                            name="amount" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-addr" class="control-label">ETH Wallet Address</label>
                                    <div>
                                        <input class="form-control" autocomplete="off" id="input-addr" type="text"
                                            name="address" />
                                    </div>
                                </div>
                                <div class="form-group">
                                        <label for="input-security_pin" class="control-label">Security Pin</label>
                                        <div>
                                            <input class="form-control" autocomplete="off" id="input-security_pin" type="password"
                                                name="security_pin" />
                                        </div>
                                    </div>
                                <div class="form-group">
                                    Withdrawal fees: {{$fee}} %
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Request withdraw</button>
                                </div>
                            </form>
                            @else
                            <div class="alert alert-info">
                                Please complete your KYC to proceed with withdrawals.
                                <br>
                                <a href="{{route('profile.index')}}#kyc">Complete now</a>
                            </div>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <div class="box-body">
                    <table id="table-approvals" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Wallet Address</th>
                                <th>Satus</th>
                                <th>Proof of Payment</th>
                            </tr>
                        </thead>
                        <tbody style="display:none;">
                            @foreach($records as $row)
                            <tr>
                                <td>{{Carbon\Carbon::parse($row['created_at'])->format('Y-m-d')}}</td>
                                <td>{{$row['content']['amount']}} ETH 
                                @isset($row['content']['fee'])
                                <br>Fees: {{$row['content']['fee']}} ETH
                                @endisset
                                </td>
                                <td>{{$row['content']['address']}}</td>
                                <td>
                                    @include('partials.approvalstatus', ['status' => $row['status']])
                                </td>
                                <td>
                                        @if($row['content'])
                                        @if(isset($row['content']['PAYMENT_TXID']))
                                        TX: <b>{{$row['content']['PAYMENT_TXID']}}</b>
                                        @endif
                                        @if(isset($row['content']['PAYMENT_FILE']))
                                        <br>
                                        <a href="{{ url($row['content']['PAYMENT_FILE'])}}" target="_blank"><img
                                                src="{{ url($row['content']['PAYMENT_FILE'])}}"
                                                class="attachment-img" /></a>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection

@section('footerscripts')
<script>
    $('#table-approvals').DataTable({
        order: [[ 0, "desc" ]]
    });
    $('#table-approvals tbody').show();
</script>
@endsection