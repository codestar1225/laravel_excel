@extends('layouts.admin')

@section('htmlheader_title')
Settings
@endsection

@section('contentheader_title')
Settings
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
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
                <form class="form-horizontal" method="POST" action="{{route('admin.settings')}}">
                    @csrf
                    @method("PATCH")
                    <div class="box-body">
                        <div class="form-group">
                            <label for="input-wallet" class="col-md-4 control-label">Registration ETH Wallet Address</label>
                            <div class="col-md-8">
                                <p>0x7D21bd03e341F256D1Ebf4bb56647278c1e7a9b3</p>
                                {{-- 
                                <input type="text" name="company_eth_wallet" class="form-control" id="input-wallet" value="{{$settings['company_eth_wallet']->val}}" placeholder="">
                                --}}
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                            <div class="form-group">
                                <label for="input-feltaratio" class="col-md-4 control-label">FLC Ratio</label>
                                <div class="col-md-4">
                                        <div class="input-group">
                                                <span class="input-group-addon">1 ETH : </span>
                                                <input type="text" name="felta_ratio" class="form-control" id="input-feltaratio" value="{{$settings['felta_ratio']->val}}" placeholder="">
                                                <span class="input-group-addon">FLC</span>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="input-withdrawal_fee" class="col-md-4 control-label">Withdrawal Fee</label>
                                <div class="col-md-4">
                                        <div class="input-group">
                                                <input type="text" name="withdrawal_fee" class="form-control" id="input-withdrawal_fee" value="{{$settings['withdrawal_fee']->val}}" placeholder="">
                                                <span class="input-group-addon">%</span>
                                        </div>
                                </div>
                            </div>
                        </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection