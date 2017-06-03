@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <img class="img-responsive home-images" src="/images/mic1.jpg" width="750">
            <img class="img-responsive home-images" src="/images/mic2.jpg" width="750">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Dashboard</h3></div>
                <div class="panel-body">
                    You are logged in! <strong>{{ Auth::user()->name }}</strong>
                    <br>
                    <br>
                    Accumulated Personal Purchase (APP): <span class="user-total-earnings"><strong>Php {{ number_format($data['app'], 2) }}</strong></span><br>
                    Accumulated Group Purchase (AGP): <span class="user-total-earnings"><strong>Php {{ number_format($data['agp'], 2) }}</strong></span><br>
                    Accumulated Direct Sponsor Commission (ADSC): <span class="user-total-earnings"><strong>Php {{ number_format($data['adsc'], 2) }}</strong></span><br>
                    Accumulated Indirect Sponsor Commission (AISC): <span class="user-total-earnings"><strong>Php {{ number_format($data['aisc'], 2) }}</strong></span><br>
                    Accumulated Matching Bonus (AMB): <span class="user-total-earnings"><strong>Php {{ number_format($data['amb'], 2) }}</strong></span><br>
                </div>
            </div>
            <img class="img-responsive home-images promo" src="/images/sfi-promo-1.jpg" width="750">
        </div>
    </div>
</div>
@endsection
