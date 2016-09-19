@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add Ranking Lions</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/rankinglion') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="description" value="{{ old('description') }}">

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('app') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">APP</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="app" value="{{ old('app') }}">

                                @if ($errors->has('app'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('app') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('agp') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">AGP</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="agp" value="{{ old('agp') }}">

                                @if ($errors->has('agp'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('agp') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('rebates_system_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Rebate System ID</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="rebates_system_id" value="{{ old('rebates_system_id') }}">

                                @if ($errors->has('rebates_system_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rebates_system_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url('/rankinglion') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
