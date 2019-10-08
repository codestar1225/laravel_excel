@extends('layouts.member')

@section('htmlheader_title')
New Member
@endsection

@section('contentheader_title')
New Member
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
                <form class="form-horizontal" method="POST" action="{{route('member.downlines.store')}}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label for="input-plan" class="col-md-3 control-label">Investment Plan</label>
                            <div class="col-md-8">
                                <select name="plan_id">
                                    @foreach($plans as $plan)
                                    <option value="{{$plan->id}}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>{{$plan->label}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input-name" class="col-md-3 control-label">Full Name</label>
                            <div class="col-md-8">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="input-name" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-username" class="col-md-3 control-label">User ID</label>
                            <div class="col-md-8">
                                <input type="text" name="username" value="{{ old('username') }}" class="form-control" id="input-username"
                                    placeholder="">
                            </div>
                        </div>
                        {{--
                        <div class="form-group">
                            <label for="input-password" class="col-md-3 control-label">Password</label>
                            <div class="col-md-8">
                                <input type="password" name="password" class="form-control" id="input-password"
                                    placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-password_confirmation" class="col-md-3 control-label">Confirm Password</label>
                            <div class="col-md-8">
                                <input type="password" name="password_confirmation" class="form-control" id="input-password_confirmation"
                                    placeholder="">
                            </div>
                        </div>--}}
                        <div class="form-group">
                                <label for="input-email" class="col-md-3 control-label">Email</label>
                                <div class="col-md-8">
                                        <input type="email" name="email" id="input-email" class="form-control" value="{{ old('email') }}" />
                                </div>
                            </div>
                        <div class="form-group">
                            <label for="input-contact" class="col-md-3 control-label">Contact Number</label>
                            <div class="col-md-8">
                                <input type="text" name="contact" value="{{ old('contact') }}" class="form-control" id="input-contact"
                                    placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-id_number" class="col-md-3 control-label">Identification Number</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <select name="id_type">
                                            @foreach($idTypes as $type)
                                            <option value="{{$type}}" {{ old('id_type') == $type ? 'selected' : '' }}>{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                    <input type="text" name="id_number" value="{{ old('id_number') }}" id="input-id_number" class="form-control">

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <a href="{{route('member.dashboard')}}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection