@extends('layouts.member')

@section('htmlheader_title')
Wallet Transactions
@endsection

@section('contentheader_title')
Wallet Transactions
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">My Wallets</h3>
                </div>
                <div class="box-body">
                    <ul class="list-group list-group-unbordered">
                        @foreach($wallets as $wallet)
                        <li class="list-group-item">
                            <b>{{$wallet['type']}} Wallet</b> <span class="pull-right">{{$wallet['balance']}}</span>
                        </li>
                        @endforeach
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
                                <th>Wallet</th>
                                <th>Amount</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody style="display:none;">
                            @foreach($transactions as $tx)
                            <tr>
                                <td>{{Carbon\Carbon::parse($tx['created_at'])->format('Y-m-d')}}</td>
                                <td>{{$tx['wallet_type']}}</td>
                                <td>{{$tx['amount']}}</td>
                                <td>{{$tx['type']}}</td>
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