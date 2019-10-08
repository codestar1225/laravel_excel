@extends('layouts.member')

@section('htmlheader_title')
Purchase Investment Plan
@endsection

@section('contentheader_title')
Purchase Investment Plan
@endsection



@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Your current plan: {{$member['plan']['label']}}</h3>
                    <p></p>
                    <p>*please top up the ETH differences of the new selected plan. </p>
                    <p style="font-size:12px">0x8b9751Ba6442842d4e977eB0Ed00186649E56F51</p>
                    <button style="color:white;" onclick="copyToClipboard('0x8b9751Ba6442842d4e977eB0Ed00186649E56F51') ? this.innerText='Copied!': this.innerText='Sorry :(' ">Click here</button>
                </div>
                <div class="box-body">
                    @if(count($upgrades) == 0)
                    <div class="alert alert-success">
                        You are at the highest investment plan.
                    </div>
                    @else
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($approval)
                    @if($approval['status'] == 0)
                    <div class="alert alert-info">
                        Your new investment plan {{$approval['ref']['label']}} is pending for approval.
                    </div>
                    @elseif($approval['status'] == 2)
                    <div class="alert alert-error">
                            Your new investment plan {{$approval['ref']['label']}} has been rejected.
                        </div>
                    @endif
                    @endif

                    <form class="form-horizontal" method="POST" enctype="multipart/form-data"
                        action="{{route('member.upgrade.update')}}">
                        @csrf

                        <div class="box-body attachments">
                                <div class="form-group">
                                        <label for="input-plan" class="col-md-4 control-label">New Investment Plan</label>
                                        <div class="col-md-8">
                                            <select name="plan_id">
                                                @foreach($upgrades as $plan)
                                                <option value="{{$plan['id']}}" {{ old('plan_id') == $plan['id'] ? 'selected' : '' }}>{{$plan['label']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group">
                                <label for="input-txid" class="col-md-4 control-label">Transaction ID</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="txid" id="txid" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-txfile" class="col-md-4 control-label">Payment Image</label>
                                <div class="col-md-8">
                                    <input type="file" class="form-control" name="txfile" id="txfile">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
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