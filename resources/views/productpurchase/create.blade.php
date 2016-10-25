@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add Product Purchase</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/productpurchase') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('ibo_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">IBO ID</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="ibo_id" value="{{ $data['user_ibo_id'] }}">

                                @if ($errors->has('ibo_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ibo_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="product-container">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Product</label>
                                <div class="col-md-6 product-dropdown">
                                    {{ Form::select('product_id[]', $data['products'], null, ['class'=>'form-control']) }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-offset-4">
                                <div class="checkbox" style="float:left; margin-right:30px;">
                                    <label>
                                        <a class="add-product" href="#">Add product</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url('/productpurchase') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
