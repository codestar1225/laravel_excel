@extends('layouts.admin')

@section('htmlheader_title')
New Plan
@endsection

@section('contentheader_title')
New Plan
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
                <form class="form-horizontal" method="POST" action="{{route('admin.plans.store')}}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label for="input-label" class="col-sm-2 control-label">Label</label>
                            <div class="col-sm-10">
                                <input type="text" name="label" class="form-control" id="input-label" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-price" class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" name="price" id="input-price" class="form-control">
                                    <span class="input-group-addon">
                                        <select name="price_type">
                                            @foreach($priceTypes as $type)
                                            <option value="{{$type}}">{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-price" class="col-sm-2 control-label">Investor Profit</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" name="user_comm" id="input-user_comm" class="form-control">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-price" class="col-sm-2 control-label">Company Profit</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" name="company_comm" id="input-company_comm"
                                        class="form-control">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-price" class="col-sm-2 control-label">Bonus</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" name="bonus" id="input-bonus"
                                        class="form-control">
                                    <span class="input-group-addon">FLC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{route('admin.plans.index')}}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection