@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Product Purchases
                    <a class="pull-right" href="{{ route('productpurchase.create') }}">Add</a>
                </div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <div class="pull-right">{!! $data->links() !!}</div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>ID</th>
                            <th>IBO ID</th>
                            <th>Product</th>
                            <th>Product Code</th>
                            <th>Purchase Amount</th>
                            <th>Date Added</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>
                                        {{ isset(App\Ibo::find($value->ibo_id)->firstname) ? App\Ibo::find($value->ibo_id)->firstname : '' }} 
                                        {{ isset(App\Ibo::find($value->ibo_id)->lastname) ? App\Ibo::find($value->ibo_id)->lastname : '' }}<br>
                                        {{ sprintf('%09d', $value->ibo_id) }}
                                    </td>
                                    <td>{{ App\Product::find($value->product_id)->name }}</td>
                                    <td>{{ isset(App\ProductCode::find($value->product_code_id)->code) ? decrypt(App\ProductCode::find($value->product_code_id)->code) : '' }}</td>
                                    <td>{{ $value->purchase_amount }}</td>
                                    <td>{{ Carbon\Carbon::parse($value->created_at)->setTimezone('Asia/Manila')->format('Y-m-d g:i:s A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">{!! $data->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
