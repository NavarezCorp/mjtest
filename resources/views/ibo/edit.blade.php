@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit IBO</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/ibo/' . $data->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-md-4 control-label">Firstname</label>

                            <div class="col-md-6">
                                <input id="firstname" type="text" class="form-control" name="firstname" value="{{ $data->firstname }}" readonly>

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('middlename') ? ' has-error' : '' }}">
                            <label for="middlename" class="col-md-4 control-label">Middlename</label>

                            <div class="col-md-6">
                                <input id="middlename" type="text" class="form-control" name="middlename" value="{{ $data->middlename }}" readonly>

                                @if ($errors->has('middlename'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('middlename') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-md-4 control-label">Lastname</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control" name="lastname" value="{{ $data->lastname }}" readonly>

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('sponsor_id') ? ' has-error' : '' }}">
                            <label for="sponsor_id" class="col-md-4 control-label">Sponsor ID</label>

                            <div class="col-md-6">
                                <input id="sponsor_id" type="text" class="form-control" name="sponsor_id" value="{{ $data->sponsor_id }}" autofocus>

                                @if ($errors->has('sponsor_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sponsor_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('placement_id') ? ' has-error' : '' }}">
                            <label for="placement_id" class="col-md-4 control-label">Placement ID</label>

                            <div class="col-md-6">
                                <input id="placement_id" type="text" class="form-control" name="placement_id" value="{{ $data->placement_id ? $data->placement_id : '' }}">

                                @if ($errors->has('placement_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('placement_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('placement_position') ? ' has-error' : '' }}">
                            <label for="placement_position" class="col-md-4 control-label">Placement position</label>

                            <div class="col-md-6">
                                <input id="placement_position" type="text" class="form-control" name="placement_position" value="{{ $data->placement_position ? $data->placement_position : '' }}">

                                @if ($errors->has('placement_position'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('placement_position') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox" style="float:left; margin-right:30px;">
                                    <label>
                                        <input type="checkbox" name="is_part_company"> Part of company
                                    </label>
                                </div>
                                <div class="checkbox" style="float:left;">
                                    <label>
                                        <input type="checkbox" name="is_admin"> Admin
                                    </label>
                                </div>
                            </div>
                        </div>
                        -->
                        <hr>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
