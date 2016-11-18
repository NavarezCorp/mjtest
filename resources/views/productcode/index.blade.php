@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Generate and Transfer Product Codes
                    <a class="pull-right" href="/productcode">New</a>
                </div>
                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="" action="" id="product-code-generator">
                        <div class="form-group">
                            <label class="col-md-4 control-label">How many character</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control" name="howmanychar" value="8" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Transfer to IBO ID</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="ibo_id" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label class="col-md-4 control-label">Product Name</label>
                                <div class="col-md-4">
                                    {{ Form::select('product_id', $data['products'], null, ['class'=>'form-control']) }}
                                </div>
                            </div>
                            <div style="float:left;">
                                <label class="col-md-4 control-label">Pieces</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="howmanyproducts" required>
                                </div>
                            </div>    
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Generate and Transfer</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <a class="btn btn-primary print-product-codes link-disabled" href="/productcode/print/generated" role="button" target="_blank">Print Codes</a>
                    <a class="pull-right" href="/productcode/all" target="_blank">All Product Codes</a>
                    <hr>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>ID</th>
                            <th>Product name</th>
                            <th>Code</th>
                            <th>Transfered to</th>
                            <th>Datetime Transfered</th>
                        </thead>
                        <tbody class="new-product-code-table"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
