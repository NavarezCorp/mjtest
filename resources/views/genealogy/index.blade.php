@extends('layouts.app')

@section('content')
<input type="hidden" id="genealogy_sponsor_id" value="{{ $data['sponsor_id'] }}">
<input type="hidden" id="genealogy_placement_id" value="{{ $data['placement_id'] }}">
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Genealogy</div>
                <div class="panel-body">
                    <div class="downline-container" style="text-align:center;">
                        <span class="left-counter" style="margin-right:25%;">Left: <strong>{{ $data['counter']['left'] }}</strong></span>
                        <span class="right-counter">Right: <strong>{{ $data['counter']['right'] }}</strong></span>
                    </div>
                    <div class="downline-container" style="text-align:center;">
                        <span class="left-counter" style="margin-right:25%;">Waiting: <strong>{{ $data['waiting']['left'] }}</strong></span>
                        <span class="right-counter">Waiting: <strong>{{ $data['waiting']['right'] }}</strong></span>
                    </div>
                    <div class="downline-container" style="text-align:center;">
                        <span class="left-counter" style="margin-right:25%;">New IBO for this week: <strong>0</strong></span>
                        <span class="right-counter">New IBO for this week: <strong>0</strong></span>
                    </div>
                    <div id="chart-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
