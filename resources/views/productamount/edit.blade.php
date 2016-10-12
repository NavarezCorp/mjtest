@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Product</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/productamount/' . $data['info']->id) }}">
                        {!! csrf_field() !!}
                        {{ method_field('PUT') }}
                        
                        <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Product</label>

                            <div class="col-md-6">
                                {{ Form::select('product_id', $data['products'], $data['info']->product_id, ['class'=>'form-control']) }}
                                
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
                                <input type="text" class="form-control" name="amount" value="{{ $data['info']->amount }}">

                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('created_by') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Created By</label>

                            <div class="col-md-6">
                                {{ Form::select('created_by', $data['users'], $data['info']->created_by, ['class'=>'form-control']) }}
                                
                                @if ($errors->has('created_by'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('created_by') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox" style="float:left; margin-right:30px;">
                                    <label>
                                        <input type="checkbox" name="is_active"> Is Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Update</button>
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
