@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Manage Product Codes</div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="" action="" id="product-code-generator">
                        <div class="form-group{{ $errors->has('is_used') ? ' has-error' : '' }}">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox" style="float:left; margin-right:30px;">
                                    <label>
                                        <input type="radio" name="transfer_to" value="pc" checked required> Product Center
                                    </label>
                                </div>
                                <div class="checkbox" style="float:left; margin-right:30px;">
                                    <label>
                                        <input type="radio" name="transfer_to" value="ms"> Mobile Stocky
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Product Center IBO ID</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="ibo_id" required>
                            </div>
                        </div>
                        <div class="form-group ms-text-box" style="display:none;">
                            <label class="col-md-4 control-label">Mobile Stocky IBO ID</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="ibo_id" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Product Name</label>
                            <div class="col-md-6">
                                {{ Form::select('product_id', $data['products'], null, ['class'=>'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">How many character</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control" name="howmanychar" value="8" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">How many products</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control" name="howmanyproducts" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Transfer</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div>
                        Generated Codes
                        <a class="pull-right" href="">Print</a>
                        <hr style="clear:both;">
                        <table class="table table-striped table-hover table-condensed">
                            <thead>
                                <th>ID</th>
                                <th>Product name</th>
                                <th>Code</th>
                                <th>Product Center IBO ID</th>
                                <th>Mobile Stocky IBO ID</th>
                                <th>Dealer IBO ID</th>
                            </thead>
                            <tbody>
                                @foreach ($data['product_codes'] as $key => $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>{{ App\Product::find($value->product_id)->name }}</td>
                                        <td>{{ decrypt($value->code) }}</td>
                                        @if (!empty($value->assigned_to_pc_ibo_id))
                                            <td>{{ sprintf('%09d', $value->assigned_to_pc_ibo_id) }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if (!empty($value->assigned_to_ms_ibo_id))
                                            <td>{{ sprintf('%09d', $value->assigned_to_ms_ibo_id) }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if (!empty($value->assigned_to_dealer_ibo_id))
                                            <td>{{ sprintf('%09d', $value->assigned_to_dealer_ibo_id) }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
