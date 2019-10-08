@extends('layouts.member')

@section('htmlheader_title')
Transfers
@endsection

@section('contentheader_title')
Transfers
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
                            <b>FLC</b> <span class="pull-right">{{$wallet['balance']}}</span>
                        </li>
                        @if($kycStatus == "1")
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
                            <form method="POST" action="{{route('member.wallets.transferout')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="input-user" class="control-label">Transfer to</label>
                                    <div>
                                        <input class="form-control" autocomplete="off" id="input-user" type="text"
                                            name="recipient" placeholder="Recipient username" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-amt" class="control-label">Transfer amount</label>
                                    <div>
                                        <input class="form-control" autocomplete="off" id="input-amt" type="number"
                                            name="amount" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Transfer</button>
                                </div>
                            </form>
                        </li>
                        @else
                            <div class="alert alert-info">
                                Please complete your KYC to proceed with withdrawals.
                                <br>
                                <a href="{{route('profile.index')}}#kyc">Complete now</a>
                            </div>
                            @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <div class="box-body">
                    <table id="table-tx" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody style="display:none;">
                            @foreach($records as $row)
                            <tr>
                                <td>{{Carbon\Carbon::parse($row['created_at'])->format('Y-m-d')}}</td>
                                <td>
                                    @if($row['type'] == 'IN')
                                    {{$row['from_wallet']['user']['name']}}
                                    @else
                                    {{$row['to_wallet']['user']['name']}}
                                    @endif
                                </td>
                                <td>{{$row['to_amount']}} FLC</td>
                                <td>{{$row['type']}}</td>
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
    $('#table-tx').DataTable({
        order: [[ 0, "desc" ]]
    });
    $('#table-tx tbody').show();
</script>
@endsection