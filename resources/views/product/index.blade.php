@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Products
                    <a class="pull-right" href="{{ route('product.create') }}">Add</a>
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
                            <th>Code</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>DateTime Added</th>
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->code }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->description }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td class="table-tools-column">
                                        <a href="{{ route('product.show', $value->id) }}">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                    </td>
                                    <td class="table-tools-column">
                                        <a href="{{ route('product.edit', $value->id) }}">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                    </td>
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