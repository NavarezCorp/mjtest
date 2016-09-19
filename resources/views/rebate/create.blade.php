@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add Rebates Systems</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/rebate') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Level</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="level" value="{{ old('level') }}">

                                @if ($errors->has('level'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('level') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('percentage') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Percentage</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="percentage" value="{{ old('percentage') }}">

                                @if ($errors->has('percentage'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('percentage') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('maintaining_purchase_amount') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Maintaining Purchase Amount</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="maintaining_purchase_amount" value="{{ old('maintaining_purchase_amount') }}">

                                @if ($errors->has('maintaining_purchase_amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('maintaining_purchase_amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url('/rebate') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
