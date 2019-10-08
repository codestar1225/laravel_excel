@extends('layouts.admin')

@section('htmlheader_title')
Proof of Payment
@endsection

@section('contentheader_title')
Proof of Payment
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
  <div class="row">
      <div class="col-md-8 col-md-offset-2">
      <div class="box box-primary">
          @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          <br />
          @endif
          
          <form class="form-horizontal" method="POST" enctype="multipart/form-data"
          action="{{route('admin.approvals.updatewithdrawal', $id)}}">
          @csrf
          @method('PATCH')
          <input type="hidden" name="status" value="1" />
          <div class="box-body attachments">
            <div class="form-group">
              <label for="input-txid" class="col-md-3 control-label">Transaction ID</label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="txid" id="txid" autocomplete="off">
              </div>
            </div>
            <div class="form-group">
              <label for="input-txfile" class="col-md-3 control-label">Payment Image</label>
              <div class="col-md-9">
                <input type="file" class="form-control" name="txfile" id="txfile">
              </div>
            </div>
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('footerscripts')
<script>
  $(document).ready(function(){
    $('#tabs-member .nav-tabs li:first').addClass("active");
    $('#tabs-member .tab-content div:first').addClass("active");
  });
</script>
@endsection