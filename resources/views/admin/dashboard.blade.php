@extends('layouts.admin')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')



<div class="container-fluid spark-screen">
    
	<div class="row">
	    <div class="col-md-12">
			<div class="small-box bg-green">
			    
				<div class="inner">
				<iframe src="https://feltacoin.com/?page_id=144" width="100%" height="200px" scrolling="no" frameborder="0"></iframe>    
				    

				    <h4 style="text-align:center">Next Payout</h4>
				    <div id="mobileHide">
				        <iframe src="https://feltacoin.com/?page_id=208" width="100%" height="830px" scrolling="no" frameborder="0"></iframe>
				    </div>
				    
				    
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
				<br>
			</div>
		</div>
		<div class="col-md-6">
			<div class="small-box bg-green">
				<div class="inner">
				    <a href="/admin/members"><h4>{{$totalMembers}}</h4></a>
					
					<p>Members</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
				<br>
			</div>
		</div>
		<div class="col-md-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
				    <a href="/admin/payouts/payouts"><h4>{{$totalSales}} ETH</h4></a>
					

					<p>Group Sales</p>
				</div>
				<div class="icon">
					<i class="fa fa-line-chart"></i>
				</div>
				<br>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Plan Sales</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<table class="table table-bordered">
						<tr>
							<th>Plan</th>
							<th>Members</th>
							<th>Sales</th>
						</tr>
						@foreach($plans as $plan)
						<tr>
							<td>{{$plan['label']}}</td>
							<td>{{$plan['total_members']}}</td>
							<td>{{$plan['total_sales']}}</td>
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection