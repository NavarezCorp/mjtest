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
                        <span class="left-counter" style="margin-right:25%;">(Old) Waiting: <strong>{{ $data['waiting']['old_left_count'] }}</strong></span>
                        <span class="right-counter">(Old) Waiting: <strong>{{ $data['waiting']['old_right_count'] }}</strong></span>
                    </div>
                    <div class="downline-container" style="text-align:center;">
                        <span class="left-counter" style="margin-right:25%;">(New) Waiting: <strong>{{ $data['waiting']['new_left_count'] }}</strong></span>
                        <span class="right-counter">(New) Waiting: <strong>{{ $data['waiting']['new_right_count'] }}</strong></span>
                    </div>
                    <div class="downline-container" style="text-align:center;">
                        <span class="left-counter" style="margin-right:25%;">New IBO for this week: <strong>{{ $data['new_ibo_this_week']['left'] }}</strong></span>
                        <span class="right-counter">New IBO for this week: <strong>{{ $data['new_ibo_this_week']['right'] }}</strong></span>
                    </div>
                    <div id="chart-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
