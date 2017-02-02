@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if(Session::has('message'))
                <div class="alert alert-success fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    {{ Session::get('message') }}
                </div>
            @endif
            <div>
                <img src="/images/sfi-promo-1.jpg" style="width:750px; margin-bottom:20px;">
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ Auth::user()->name }}</strong></div>
                <div class="panel-body">
                    Accumulated Personal Purchase (APP): <span class="user-total-earnings"><strong>Php {{ number_format($data['app'], 2) }}</strong></span><br>
                    Accumulated Group Purchase (AGP): <span class="user-total-earnings"><strong>Php 0.00</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
