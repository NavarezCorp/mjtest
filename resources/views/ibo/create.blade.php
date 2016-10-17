@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New member</div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">Account information</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/ibo') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="cid" id="cid" value="">
                                <input type="hidden" name="activation_code_type" id="activation_code_type" value="">
                                <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                    <label for="firstname" class="col-md-4 control-label">Firstname</label>

                                    <div class="col-md-6">
                                        <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

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
                                        <input id="middlename" type="text" class="form-control" name="middlename" value="{{ old('middlename') }}" required>

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
                                        <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>

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
                                        <input id="sponsor_id" type="text" class="form-control" name="sponsor_id" value="{{ old('sponsor_id') }}" required>

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
                                        <input id="placement_id" type="text" class="form-control" name="placement_id" value="{{ $data['placement_id'] ? sprintf('%09d', $data['placement_id']) : '' }}" required>

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
                                        <input id="placement_position" type="text" class="form-control" name="placement_position" value="{{ $data['placement_position'] ? $data['placement_position'] : '' }}" required>

                                        @if ($errors->has('placement_position'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('placement_position') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('activation_code') ? ' has-error' : '' }}">
                                    <label for="placement_id" class="col-md-4 control-label">Activation Code</label>

                                    <div class="col-md-5">
                                        <input id="activation_code" type="text" class="form-control" name="activation_code" value="{{ old('activation_code') }}" style="text-transform:uppercase" required>

                                        @if ($errors->has('activation_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('activation_code') }}</strong>
                                            </span>
                                        @endif
                                        <span class="help-block code-help-block" style="display:none;"></span>
                                    </div>
                                    <button type="button" class="btn btn-default code-search">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary register-button" disabled>
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
