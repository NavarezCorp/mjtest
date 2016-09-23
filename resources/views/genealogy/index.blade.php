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
                    <div id="chart-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
