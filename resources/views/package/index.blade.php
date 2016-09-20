@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Packages
                    <a class="pull-right" href="{{ route('package.create') }}">Add</a>
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
                            <th>Activation Code</th>
                            <th>Package Type ID</th>
                            <th>Created By</th>
                            <th>Is Used</th>
                            <th>DateTime Used</th>
                            <th>Used By</th>
                            <th>Encoded By</th>
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->activation_code }}</td>
                                    <td>{{ $value->package_type_id }}</td>
                                    <td>{{ $value->created_by }}</td>
                                    <td>{{ $value->is_used }}</td>
                                    <td>{{ $value->datetime_used }}</td>
                                    <td>{{ $value->used_by_ibo_id }}</td>
                                    <td>{{ $value->encoded_by_ibo_id }}</td>
                                    <td class="table-tools-column">
                                        <a href="{{ route('package.show', $value->id) }}">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                    </td>
                                    <td class="table-tools-column">
                                        <a href="{{ route('package.edit', $value->id) }}">
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
