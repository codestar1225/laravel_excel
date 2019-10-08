@extends('layouts.member')

@section('htmlheader_title')
Payout Details
@endsection

@section('contentheader_title')
Payout Details
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
  <div class="row">
    <div class="col-md-4">
      <div class="box box-primary">
        <div class="box-body box-profile">
          <h3 class="profile-username text-center">
            {{Carbon\Carbon::parse($payout['created_at'])->format('Y-m-d')}}
          </h3>
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Rank</b><span class="pull-right">{{$rank}}</span>
            </li>
            <li class="list-group-item">
              <b>Profit</b> <span class="pull-right">{{$payout['rate']}} %</span>
            </li>
            <li class="list-group-item">
              <b>Interest</b> <span class="pull-right">{{$payout['interest_payments']['ETH']}} ETH </span>
            </li>
            <li class="list-group-item">
              <span>&nbsp;</span><span class="pull-right">{{$payout['interest_payments']['FELTA']}} FLC </span>
            </li>
            <li class="list-group-item">
              <b>Override Bonus</b> <span class="pull-right">{{$payout['override_payments']['ETH']}} ETH </span>
            </li>
            <li class="list-group-item">
              <span>&nbsp;</span><span class="pull-right">{{$payout['override_payments']['FELTA']}} FLC </span>
            </li>
            <li class="list-group-item">
              <b>Ranking Bonus</b> <span class="pull-right">{{$payout['ranking_payments']['ETH']}} ETH </span>
            </li>
            <li class="list-group-item">
              <span>&nbsp;</span><span class="pull-right">{{$payout['ranking_payments']['FELTA']}} FLC </span>
            </li>
            <li class="list-group-item">
              <b>Group Rebates</b> <span class="pull-right">{{$payout['rebates']['FELTA']}} FLC </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h3>Downline Contributions</h3>
        </div>
        <div class="box-body">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style="width:120px;">Member</th>
                <th>Override Bonus</th>
                <th>Ranking Bonus</th>
                <th>Group Rebate</th>
              </tr>
            </thead>
            <tbody style="display:none;">
              @foreach($downlines as $r)
              <tr>
                <td>{{$r['name']}}</td>
                <td>{{$r['OVRINT']['ETH']}} ETH, {{$r['OVRINT']['FELTA']}} FELTA</td>
                <td>{{$r['RNKINT']['ETH']}} ETH, {{$r['RNKINT']['FELTA']}} FELTA</td>
                <td>{{$r['RBTINT']['ETH']}} ETH</td>
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
  $(document).ready(function(){
    $('#tabs-details .nav-tabs li:first').addClass("active");
    $('#tabs-details .tab-content div:first').addClass("active");
    $('.table').DataTable();
    $('.table tbody').show();
  });
</script>
@endsection