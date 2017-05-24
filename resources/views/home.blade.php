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
                <img src="/images/mic1.jpg" style="width:750px; margin-bottom:20px;">
            </div>
            <div>
                <img src="/images/mic2.jpg" style="width:750px; margin-bottom:20px;">
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    You are logged in! <strong>{{ Auth::user()->name }}</strong>
                    <br>
                    <br>
                    Accumulated Personal Purchase (APP): <span class="user-total-earnings"><strong>Php {{ number_format($data['app'], 2) }}</strong></span><br>
                    Accumulated Group Purchase (AGP): <span class="user-total-earnings"><strong>Php {{ number_format($data['agp'], 2) }}</strong></span><br>
                    Accumulated Direct Sponsor Commission (ADSC): <span class="user-total-earnings"><strong>Php {{ number_format($data['adsc'], 2) }}</strong></span><br>
                    Accumulated Indirect Sponsor Commission (AISC): <span class="user-total-earnings"><strong>Php {{ number_format(0, 2) }}</strong></span><br>
                    Accumulated Matching Bonus (AMB): <span class="user-total-earnings"><strong>Php {{ number_format(0, 2) }}</strong></span><br>
                </div>
            </div>
            <!--
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ Auth::user()->name }}</strong></div>
                <div class="panel-body">
                    Accumulated Personal Purchase (APP): <span class="user-total-earnings"><strong>Php {{ number_format($data['app'], 2) }}</strong></span><br>
                    Accumulated Group Purchase (AGP): <span class="user-total-earnings"><strong>Php {{ number_format($data['agp'], 2) }}</strong></span>
                </div>
            </div>
            -->
            <div>
                <img src="/images/sfi-promo-1.jpg" style="width:750px; margin-bottom:20px;">
            </div>
        </div>
    </div>
</div>
@endsection
