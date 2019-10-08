@extends('layouts.admin')

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
              <b>Rate</b> <span class="pull-right">{{$payout['rate']}} %</span>
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
              <b>Group Rebate Rate</b> <span class="pull-right">{{$payout['rebate_rate']}} %</span>
            </li>
            <li class="list-group-item">
              <b>Group Rebates</b> <span class="pull-right">{{$payout['rebates']}} ETH </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="nav-tabs-custom" id="tabs-details">
        <ul class="nav nav-tabs">
          <li><a href="#interest" data-toggle="tab" aria-expanded="true">Interest</a></li>
          <li><a href="#override" data-toggle="tab" aria-expanded="true">Override Bonus</a></li>
          <li><a href="#ranking" data-toggle="tab" aria-expanded="true">Ranking Bonus</a></li>
          <li><a href="#rebates" data-toggle="tab" aria-expanded="true">Group Rebates</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane" id="interest">
            <div class="box">
              <div class="box-body">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th style="width:120px;">Member</th>
                      <th>Plan</th>
                      <th>Amount</th>
                      <th>Wallet</th>
                    </tr>
                  </thead>
                  <tbody style="display:none;">
                    @foreach($interests as $tx)
                    <tr>
                      <td>{{$tx['wallet']['user']['name']}}</td>
                      <td>{{$tx['extra']}}</td>
                      <td>{{$tx['amount']}}</td>
                      <td>{{$tx['wallet']['type']}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="override">
            <div class="box">
              <div class="box-body">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th style="width:120px;">Member</th>
                      <th>Amount</th>
                      <th>Wallet</th>
                    </tr>
                  </thead>
                  <tbody style="display:none;">
                    @foreach($overrides as $tx)
                    <tr>
                      <td>{{$tx['wallet']['user']['name']}}</td>
                      <td>{{$tx['amount']}}</td>
                      <td>{{$tx['wallet']['type']}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="ranking">
            <div class="box">
              <div class="box-body">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th style="width:120px;">Member</th>
                      <th>Amount</th>
                      <th>Wallet</th>
                      <th>Rank</th>
                    </tr>
                  </thead>
                  <tbody style="display:none;">
                    @foreach($rankings as $tx)
                    <tr>
                      <td>{{$tx['wallet']['user']['name']}}</td>
                      <td>{{$tx['amount']}}</td>
                      <td>{{$tx['wallet']['type']}}</td>
                      <td>{{$tx['extra']}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="rebates">
            <div class="box">
              <div class="box-body">
                  <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th style="width:120px;">Member</th>
                          <th># New Downlines</th>
                          <th>Amount</th>
                          <th>Wallet</th>
                        </tr>
                      </thead>
                      <tbody style="display:none;">
                        @foreach($rebates as $tx)
                        <tr>
                          <td>{{$tx['wallet']['user']['name']}}</td>
                        <td>{{count($tx['extra'])}}</td>
                          <td>{{$tx['amount']}}</td>
                          <td>{{$tx['wallet']['type']}}</td>
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