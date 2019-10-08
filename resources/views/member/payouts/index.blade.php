@extends('layouts.member')

@section('htmlheader_title')
Payouts
@endsection

@section('contentheader_title')
Payouts
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table id="table-payouts" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width:60px;">Date</th>
                                <th>Profit</th>
                                <th>Interest</th>
                                <th>Override Bonus</th>
                                <th>Ranking Bonus</th>
                                <th>Group Rebates</th>
                                <th style="width:60px;"></th>
                            </tr>
                        </thead>
                        <tbody style="display:none;">
                            @foreach($payouts as $payout)
                            <tr>
                                <td>{{Carbon\Carbon::parse($payout['created_at'])->format('Y-m-d')}}</td>
                                <td>{{$payout['rate']}} %</td>
                                <td>{{$payout['interest_payments']['ETH']}} ETH , {{$payout['interest_payments']['FELTA']}} FLC</td>
                                <td>{{$payout['override_payments']['ETH']}} ETH , {{$payout['override_payments']['FELTA']}} FLC</td>
                                <td>{{$payout['ranking_payments']['ETH']}} ETH , {{$payout['ranking_payments']['FELTA']}} FLC</td>
                                <td>{{$payout['rebates']['ETH']}} ETH</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('member.payouts.show',$payout['id'])}}"
                                                class="btn btn-block btn-primary">View</a>
                                        </div>
                                    </div>
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
    $('#table-payouts').DataTable({
        filter:false,
        order: [[ 0, "desc" ]]
    });
    $('#table-payouts tbody').show();
</script>
@endsection