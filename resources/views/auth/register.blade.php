@extends('layouts.auth')

@section('htmlheader_title')
Register
@endsection

@section('content')

<body class="hold-transition register-page"
    style="width:100%; height:100%; padding-top:60px; background-image: url(https://feltacoin.com/wp-content/uploads/2019/06/line_black_background_route_map_63533_1920x1080.jpg); height:100% ">
    <div id="app" v-cloak>
        <div class="register-box">
            <div class="register-logo">
                <a href="http://feltacoin.com/"><img src="http://feltacoin.com/logo-7.png" width=70%></a>
            </div><!-- /.login-logo -->

            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> {{ trans('adminlte_lang::message.someproblems') }}<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="register-box-body">
                <p class="login-box-msg">{{ trans('adminlte_lang::message.registermember') }}</p>
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label for="input-plan" class="control-label">Investment Plan</label><br>
                            <select name="plan_id">
                                @foreach($plans as $plan)
                                <option value="{{$plan->id}}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{$plan->label}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="input-name" class="control-label">Full Name</label>
                            <br>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                id="input-name" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="input-username" class="control-label">User ID</label><br>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control"
                                id="input-username" placeholder="">
                        </div>
                        {{--
                        <div class="form-group">
                            <label for="input-password" class="control-label">Password</label><br>
                            <input type="password" name="password" class="form-control" id="input-password"
                                placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="input-password_confirmation" class="control-label">Confirm
                                Password</label><br>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="input-password_confirmation" placeholder="">
                        </div> --}}
                        <div class="form-group">
                            <label for="input-email" class="control-label">Email</label><br>
                            <input type="email" name="email" id="input-email" class="form-control"
                                value="{{ old('email') }}" />
                        </div>
                        <div class="form-group">
                            <label for="input-contact" class="control-label">Contact Number</label><br>
                            <input type="text" name="contact" value="{{ old('contact') }}" class="form-control"
                                id="input-contact" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="input-id_number" class="control-label">Identification Number</label><br>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <select name="id_type">
                                        @foreach($idTypes as $type)
                                        <option value="{{$type}}" {{ old('id_type') == $type ? 'selected' : '' }}>
                                            {{$type}}</option>
                                        @endforeach
                                    </select>
                                </span>
                                <input type="text" name="id_number" value="{{ old('id_number') }}" id="input-id_number"
                                    class="form-control">

                            </div>
                        </div>
                        <div class="form-group">
                                <label for="input-sponsor" class="control-label">Sponsor</label><br>
                                <input type="text" name="sponsor" value="{{ old('sponsor', $sponsor) }}" class="form-control"
                                    id="input-sponsor" placeholder="">
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button type="submit"
                                class="btn btn-primary btn-block btn-flat">{{ trans('adminlte_lang::message.register') }}</button>
                        </div>
                    </div>
                </form>
            </div><!-- /.form-box -->
        </div><!-- /.register-box -->
    </div>

    @include('adminlte::layouts.partials.scripts_auth')

</body>

@endsection