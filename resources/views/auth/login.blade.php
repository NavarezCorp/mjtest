@extends('layouts.app')

@section('content')
<div class="container">
    <div class="login-card">
        <img src="images/lion3.jpg" class="profile-img-card"/>
        <p class="profile-name-card"></p>
        <form class="form-horizontal form-signin" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}
            
            <input class="form-control" type="text" required placeholder="IBO ID" autofocus id="ibo_id" name="ibo_id" value="{{ old('ibo_id') }}"/>
            @if ($errors->has('ibo_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('ibo_id') }}</strong>
                </span>
            @endif
            
            <input class="form-control" type="password" required placeholder="Password" id="password" name="password"/>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
            
            <div class="checkbox">
                <div class="checkbox">
                    <label><input type="checkbox" name="remember"/>Remember me</label>
                </div>
            </div>
            
            <button class="btn btn-primary btn-block btn-lg btn-signin" type="submit">Sign in</button>
        </form>
        <a href="{{ url('/password/reset') }}" class="forgot-password btn-link">Forgot your password?</a>
    </div>
</div>
@endsection
