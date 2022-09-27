@extends('layouts.login')

@section('content')

    <div class="signin-box">
        <h2 class="slim-logo"><img src="{{asset('assets/images/logo.png')}}"></h2>
        <h2 class="signin-title-primary">Welcome back!</h2>
        <h3 class="signin-title-secondary">Sign in to continue.</h3>


        @if (session('message'))
            <div class="pb-5">
                <span class="help-block"> {{ session('message') }}   </span>
            </div>
        @endif


        <form method="POST" action="{{ route('login') }}">
            @csrf


            <div class="form-group">

                <span class="invalid-feedback">
                    <strong>These credentials do not match our records.</strong>
                </span>


                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                         <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif


            </div><!-- form-group -->
            <div class="form-group mg-b-50">

                <input id="password" type="password"
                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required
                       placeholder="Enter your password">
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif


            </div><!-- form-group -->
            <button class="btn btn-primary btn-block btn-signin">Sign In</button>

        </form>

    </div><!-- signin-box -->



@endsection
