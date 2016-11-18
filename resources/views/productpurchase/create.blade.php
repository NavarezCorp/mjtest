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
                                <input type="text" class="form-control" name="ibo_id" required>

                                @if ($errors->has('ibo_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ibo_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="product-container" id="ctr-1">
                            <div class="form-group">
                                <input type="hidden" name="pcid[]" id="pcid-1" value="">
                                <label class="col-md-4 control-label">Product Code</label>
                                <div class="col-md-6 product-dropdown">
                                    <input id="product-code-text-1" type="text" class="form-control product-purchase-code" name="product_code[]" style="width:400px; float:left; margin-right:10px;">
                                    <button type="button" class="btn btn-default product-code-search" id="product-code-button-1">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                    </button>
                                    <span class="help-block code-help-block-1" style="display:none;"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-offset-4">
                                <div class="checkbox" style="float:left; margin-right:30px;">
                                    <label>
                                        <a class="add-product link-disabled" href="#">Add product</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary register-button" disabled>Submit</button>
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
