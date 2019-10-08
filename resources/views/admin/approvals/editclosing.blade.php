@extends('layouts.admin')

@section('htmlheader_title')
Closing Details
@endsection

@section('contentheader_title')
Closing Details
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
                <form class="form-horizontal" method="POST"
                    action="{{route('admin.approvals.updateclosing', $approval['id'])}}">
                    @csrf
                    @method("PATCH")
                    <input type="hidden" name="status" value="1" />
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Member</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{$approval['user']['name']}}" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Plan</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{$approval['user']['plan']['label']}}"
                                    disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Sponsor</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{$approval['user']['sponsor']['name']}}"
                                    disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">ETH Balance</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{$approval['ETH_wallet']}}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">FLC Balance</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{$approval['FELTA_wallet']}}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">ETH Wallet Address</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{$approval['content']['address']}}"
                                    disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-label" class="col-sm-4 control-label">Closing Fee</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" name="fee" value="{{$approval['content']['feePct']}}"
                                        class="form-control" id="input-label" placeholder=""
                                        {{ $approval['status'] == '1' ? 'disabled' : '' }}>
                                    <span class="input-group-addon">
                                        %
                                    </span>
                                </div>
                                @if($approval['status'] == '1')
                                <br>
                                <div class="input-group">
                                    <input type="number" name="fee" value="{{$approval['content']['fee']}}"
                                        class="form-control" id="input-label" placeholder=""
                                        {{ $approval['status'] == '1' ? 'disabled' : '' }}>
                                    <span class="input-group-addon">
                                        ETH
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                    @if($approval['status'] == '0')
                    <div class="box-footer">
                        <a href="{{route('admin.approvals.closings')}}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection