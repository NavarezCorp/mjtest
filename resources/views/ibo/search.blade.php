@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">IBO Search</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="ibo_id" class="col-md-3 control-label">IBO ID</label>
                            <div class="col-md-6"><input id="ibo_id" type="text" class="form-control" name="ibo_id"></div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-3 control-label">Name</label>
                            <div class="col-md-6"><input id="name" type="text" class="form-control" name="name"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button class="btn btn-primary ibo-search-button">Search</button>
                                <img class="ibo-search-loading" src="/images/loading.gif">
                            </div>
                        </div>
                    <form>
                    <div class="ibo-search-display"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
