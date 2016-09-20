@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add Product</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/productamount') }}">
                        {!! csrf_field() !!}
                        
                        <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Product ID</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="product_id" value="{{ old('product_id') }}">

                                @if ($errors->has('product_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Amount</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="amount" value="{{ old('amount') }}">

                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Is Active</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="is_active" value="{{ old('is_active') }}">

                                @if ($errors->has('is_active'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('is_active') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('created_by') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Created By</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="created_by" value="{{ old('created_by') }}">

                                @if ($errors->has('created_by'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('created_by') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url('/productamount') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
