@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add Package</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/package') }}">
                        {!! csrf_field() !!}
                        
                        <div class="form-group{{ $errors->has('activation_code') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Activation Code</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="activation_code" value="{{ old('activation_code') }}">

                                @if ($errors->has('activation_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activation_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('package_type_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Package Type</label>

                            <div class="col-md-6">
                                {{ Form::select('package_type_id', $data['package_types'], null, ['class'=>'form-control']) }}
                                
                                @if ($errors->has('package_type_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('package_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('created_by') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Created By</label>

                            <div class="col-md-6">
                                {{ Form::select('created_by', $data['users'], null, ['class'=>'form-control']) }}
                                
                                @if ($errors->has('created_by'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('created_by') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('datetime_used') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">DateTime Used</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="datetime_used" value="{{ date("Y-m-d H:i:s") }}">

                                @if ($errors->has('datetime_used'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('datetime_used') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('used_by_ibo_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Used By IBO ID</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="used_by_ibo_id" value="{{ old('used_by_ibo_id') }}">

                                @if ($errors->has('used_by_ibo_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('used_by_ibo_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('encoded_by_ibo_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Encoded By IBO ID</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="encoded_by_ibo_id" value="{{ old('encoded_by_ibo_id') }}">

                                @if ($errors->has('encoded_by_ibo_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('encoded_by_ibo_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('is_used') ? ' has-error' : '' }}">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox" style="float:left; margin-right:30px;">
                                    <label>
                                        <input type="checkbox" name="is_active"> Is Used
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url('/package') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
