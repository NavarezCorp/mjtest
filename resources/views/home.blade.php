@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ Auth::user()->name }}</strong></div>
                <div class="panel-body">
                    Total earnings: <span class="user-total-earnings"><strong>0.00</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
