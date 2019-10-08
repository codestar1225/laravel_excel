@extends('layouts.admin')

@section('htmlheader_title')
New Payout
@endsection

@section('contentheader_title')
New Payout
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box">
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
                <form class="form-horizontal" method="POST" action="{{route('admin.payouts.store')}}">
                    @csrf
                    <div class="box-body">
                        <h4>Interest</h4>
                        <div class="form-group">
                            <label for="input-label" class="col-sm-2 control-label">Rate</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="number" name="rate" class="form-control" id="input-label"
                                        placeholder="">
                                    <span class="input-group-addon">
                                        %
                                    </span>
                                </div>

                            </div>
                        </div>
                        <hr />
                        <h4>Group Rebate</h4>
                        <div class="form-group">
                            <label for="input-rebate" class="col-sm-2 control-label">Rate</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="number" name="rebate" class="form-control" id="input-label"
                                        placeholder="">
                                    <span class="input-group-addon">
                                        %
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input-rebatemembers" class="col-sm-2 control-label">Members</label>
                            <div class="col-sm-10">
                                <select style="display:none;" class="form-control select2" name="rebatemembers[]"
                                    multiple="multiple" data-placeholder="Select a member" style="width: 100%;">
                                    @foreach($members as $m)
                                    @if($m['has_grouprebate'])
                                    <option value="{{$m['id']}}" selected>{{$m['username']}}</option>
                                    @else
                                    <option value="{{$m['id']}}">{{$m['username']}}</option>
                                    @endif

                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{route('admin.payouts.index')}}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerscripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
    
</script>
@endsection