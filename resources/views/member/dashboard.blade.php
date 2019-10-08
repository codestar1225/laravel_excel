@extends('layouts.member')

@section('htmlheader_title')
Dashboard
@endsection

@section('contentheader_title')
Member Dashboard
@endsection

@section('main-content')


<div class="container-fluid spark-screen">
	@if($announcement)
	<div class="row">
	    <div class="col-md-12">
			<div class="small-box bg-green">
				<div class="inner">
				    <iframe src="https://feltacoin.com/?page_id=144" width="100%" height="200px" scrolling="no" frameborder="0"></iframe>
				    <h4 style="text-align:center">Next Payout</h4>
				    <iframe src="https://feltacoin.com/?page_id=208" width="100%" height="600px" scrolling="no" frameborder="0"></iframe> 
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
				<br>
			</div>
		</div>
		<div class="col-md-12">
			<div class="small-box bg-green">
				<div class="box-header">
					<h3 class="box-title">Announcements</h3>
					<div class="box-tools pull-right">
						<a href="{{route('member.mailbox.announcements')}}">View All</a>
					</div>
				</div>
				<div class="box-body announcement">
					<div class="mailbox-read-info">
						<h3>{{$announcement['subject']}}

						</h3>
					</div>
					<div class="mailbox-read-message">
						{!! $announcement['body'] !!}
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
	<!-- ./col -->

	<div class="row">
		<!-- ./col -->
		<div class="col-md-3">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<a href="/member/wallets/transactions">
						<h4>{{$eth}} ETH</h4>
					</a>
					<a href="/member/wallets/transactions">
						<h4>{{$felta}} FLC</h4>
					</a>
					<p>Wallets</p>
				</div>
				<div class="icon">
					<i class="fa fa-briefcase"></i>
				</div>
				<br>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-md-3">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h4>{{$ethIncome}} ETH</h4>
					<h4>{{$feltaIncome}} FLC</h4>
					<p>Total Income</p>
				</div>
				<div class="icon">
					<i class="fa fa-briefcase"></i>
				</div>
				<br>
			</div>
		</div>

		<!-- ./col -->
		<div class="col-md-2">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h4>&nbsp;</h4>
					<a href="/member/downlines/list">
						<h4>{{$downlines}}</h4>
					</a>


					<p>Downline Members</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
				<br>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-md-2">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h4>&nbsp;</h4>
					<a href="{{route('profile.index')}}">
						<h4>{{$plan}}</h4>
					</a>


					<p>Current Investment</p>
				</div>
				<div class="icon">
					<i class="fa fa-line-chart"></i>
				</div>
				<br>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-md-2">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h4>&nbsp;</h4>
					<a href="{{route('profile.index')}}">
						<h4>{{$rank}}</h4>
					</a>


					<p>Ranking</p>
				</div>
				<div class="icon">
					<i class="fa fa-line-chart"></i>
				</div>
				<br>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-md-3">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h4>{{number_format(\App\Setting::where('option', 'felta_ratio')->first()->val)}} FLC : 1 ETH</h4>
					<p>Current Rate</p>
				</div>
				<div class="icon">
					<i class="fa fa-money"></i>
				</div>
				<br>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-md-3">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h4>{{$sales}} ETH</h4>

					<p>Group Sales</p>
				</div>
				<div class="icon">
					<i class="fa fa-line-chart"></i>
				</div>
				<br>
			</div>
		</div>

		<div class="col-md-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
				    <h4><input style="width:100%; border: none;" type="text" value="{{$reflink}}" id="myInput" readonly></h4>
				   
				    


<h4><button style="padding:5px;" onclick="copyToClipboard('{{$reflink}}') ? this.innerText='Copied!': this.innerText='Sorry :(' ">Copy Link</button></h4>


				</div>
				<div class="icon">
					<i class="fa fa-line-chart"></i>
				</div>
				<br>
			</div>
		</div>
	</div>
	@endsection

	@section('footerscripts')
	<script>
/**
 * Copy a string to clipboard
 * @param  {String} string         The string to be copied to clipboard
 * @return {Boolean}               returns a boolean correspondent to the success of the copy operation.
 */
function copyToClipboard(string) {
  let textarea;
  let result;

  try {
    textarea = document.createElement('textarea');
    textarea.setAttribute('readonly', true);
    textarea.setAttribute('contenteditable', true);
    textarea.style.position = 'fixed'; // prevent scroll from jumping to the bottom when focus is set.
    textarea.value = string;

    document.body.appendChild(textarea);

    textarea.focus();
    textarea.select();

    const range = document.createRange();
    range.selectNodeContents(textarea);

    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);

    textarea.setSelectionRange(0, textarea.value.length);
    result = document.execCommand('copy');
  } catch (err) {
    console.error(err);
    result = null;
  } finally {
    document.body.removeChild(textarea);
  }

  // manual copy fallback using prompt
  if (!result) {
    const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
    const copyHotkey = isMac ? '⌘C' : 'CTRL+C';
    result = prompt(`Press ${copyHotkey}`, string); // eslint-disable-line no-alert
    if (!result) {
      return false;
    }
  }
  return true;
}
</script>
	@endsection