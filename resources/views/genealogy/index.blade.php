@extends('layouts.app')

@section('content')
<input type="hidden" id="genealogy_ibo_id" value="{{ $data['ibo_id'] }}">
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
