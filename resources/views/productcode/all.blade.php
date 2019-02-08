@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">All Product Codes</div>
                <div class="panel-body">
                    <a class="btn btn-primary print-all-product-codes {{ $data['disable_print'] }}" href="/productcode/print/all" role="button" target="_blank">Print Codes</a>
                    <hr>
                    <div class="pull-right">{!! $data['product_codes']->appends(['product_id'=>$data['where']['product_id'], 'transfered_to'=>$data['where']['assigned_to_pc_ibo_id']])->links() !!}</div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <td></td>
                                <td>{{ Form::select('product_id', $data['products'], $data['where']['product_id'], ['class'=>'form-control product-code-product-id', 'placeholder'=>'Select a Product Name']) }}</td>
                                <td></td>
                                <td>
                                    {{-- Form::select('transfered_to', $data['ibos'], null, ['class'=>'form-control']) --}}
                                    <select class="form-control product-code-transfered-to" name="transfered_to">
                                        <option selected value="">Select an IBO ID</option>
                                        @foreach ($data['ibos'] as $value)
                                            <option value="{{ $value->id }}" {{ ($value->id == $data['where']['assigned_to_pc_ibo_id']) ? 'selected' : '' }}>{{ sprintf('%09d', $value->id) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th>Product name</th>
                                <th>Code</th>
                                <th>Transfered to</th>
                                <th>Datetime Transfered</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['product_codes'] as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ App\Product::find($value->product_id)->name }}</td>
                                    <td>{{ decrypt($value->code) }}</td>
                                    <td>
                                        {{ isset(App\Ibo::find($value->assigned_to_pc_ibo_id)->firstname) ? App\Ibo::find($value->assigned_to_pc_ibo_id)->firstname : '' }}
                                        {{ isset(App\Ibo::find($value->assigned_to_pc_ibo_id)->middlename) ? App\Ibo::find($value->assigned_to_pc_ibo_id)->middlename : '' }}
                                        {{ isset(App\Ibo::find($value->assigned_to_pc_ibo_id)->lastname) ? App\Ibo::find($value->assigned_to_pc_ibo_id)->lastname : '' }}<br>
                                        ({{ sprintf('%09d', $value->assigned_to_pc_ibo_id) }})
                                        {{ isset(App\User::where('ibo_id', $value->assigned_to_pc_ibo_id)->first()->role) ? App\User::where('ibo_id', $value->assigned_to_pc_ibo_id)->first()->role : 'Dealer' }}
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($value->created_at)->setTimezone('Asia/Manila')->format('Y-m-d g:i:s A') }}</td>
                                    <td>{{ isset($value->assigned_to_dealer_ibo_id) ? 'used' : '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">{!! $data['product_codes']->appends(['product_id'=>$data['where']['product_id'], 'transfered_to'=>$data['where']['assigned_to_pc_ibo_id']])->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
