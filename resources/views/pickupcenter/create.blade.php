@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add Pickup Center</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/pickupcenter') }}">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }} required">
                            <label for="country_id" class="col-md-4 control-label">Country</label>
                            <div class="col-md-4">
                                {{ Form::select('country_id', $data['countries'], null, ['class'=>'form-control', 'placeholder'=>'']) }}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('city_id') ? ' has-error' : '' }} required">
                            <label for="city_id" class="col-md-4 control-label">City</label>
                            <div class="col-md-3">
                                {{ Form::select('city_id', $data['cities'], null, ['class'=>'form-control', 'placeholder'=>'']) }}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('branch') ? ' has-error' : '' }} required">
                            <label for="branch" class="col-md-4 control-label">Branch</label>
                            <div class="col-md-4">
                                <input id="branch" type="text" class="form-control" name="branch" value="{{ old('branch') }}" required autofocus>
                                @if ($errors->has('branch'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('branch') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url('/pickupcenter') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection