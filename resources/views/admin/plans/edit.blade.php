@extends('layouts.admin')

@section('htmlheader_title')
Edit Plan
@endsection

@section('contentheader_title')
Edit Plan - <i>{{$plan->label}}</i>
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
                <form class="form-horizontal" method="POST" action="{{route('admin.plans.update', $plan->id)}}">
                    @csrf
                    @method("PATCH")
                    <div class="box-body">
                        <div class="form-group">
                            <label for="input-label" class="col-sm-2 control-label">Label</label>
                            <div class="col-sm-10">
                                <input type="text" name="label" class="form-control" id="input-label" value="{{$plan->label}}" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-price" class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" value="{{$plan->price}}" name="price" id="input-price" class="form-control">
                                    <span class="input-group-addon">
                                        <select name="price_type">
                                            @foreach($priceTypes as $type)
                                            @if($type == $plan->price_type)
                                            <option value="{{$type}}" selected>{{$type}}</option>
                                            @else
                                            <option value="{{$type}}">{{$type}}</option>
                                            @endif
                                            
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
                                    <input type="number" value="{{$plan->user_comm}}" name="user_comm" id="input-user_comm" class="form-control">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-price" class="col-sm-2 control-label">Company Profit</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" value="{{$plan->company_comm}}" name="company_comm" id="input-company_comm"
                                        class="form-control">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-price" class="col-sm-2 control-label">FLC Bonus</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" value="{{$plan->bonus}}" name="bonus" id="input-bonus"
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