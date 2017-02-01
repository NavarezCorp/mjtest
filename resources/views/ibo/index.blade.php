@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage IBOs
                    <a class="pull-right" href="{{ route('ibo.create') }}">Add</a>
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
                            <th>IBO ID</th>
                            <th>Date Encoded</th>
                            <th>Firstname</th>
                            <!--<th>Middle</th>-->
                            <th>Lastname</th>
                            <th>S. ID</th>
                            <th>P. ID</th>
                            <th>R. By</th>
                            <th>P.P.</th>
                            <th>Type</th>
                            <th>R.L.</th>
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>{{ sprintf('%09d', $value->id) }}</td>
                                    <td>{{ Carbon\Carbon::parse($value->created_at)->setTimezone('Asia/Manila')->format('Y-m-d g:i:s A') }}</td>
                                    <td>{{ $value->firstname }}</td>
                                    <!--<td>{{-- $value->middlename --}}</td>-->
                                    <td>{{ $value->lastname }}</td>
                                    <td>
                                        {{ isset(App\Ibo::find($value->sponsor_id)->firstname) ? App\Ibo::find($value->sponsor_id)->firstname : '' }} 
                                        {{ isset(App\Ibo::find($value->sponsor_id)->lastname) ? App\Ibo::find($value->sponsor_id)->lastname : '' }}<br>
                                        {{ isset($value->sponsor_id) ? '(' . sprintf('%09d', $value->sponsor_id) . ')' : '' }}
                                    </td>
                                    <td>
                                        {{ isset(App\Ibo::find($value->placement_id)->firstname) ? App\Ibo::find($value->placement_id)->firstname : '' }} 
                                        {{ isset(App\Ibo::find($value->placement_id)->lastname) ? App\Ibo::find($value->placement_id)->lastname : '' }}<br>
                                        {{ isset($value->placement_id) ? '(' . sprintf('%09d', $value->placement_id) . ')' : '' }}
                                    </td>
                                    <td>
                                        {{ isset(App\Ibo::find($value->registered_by)->firstname) ? App\Ibo::find($value->registered_by)->firstname : '' }}
                                        {{ isset(App\Ibo::find($value->registered_by)->lastname) ? App\Ibo::find($value->registered_by)->lastname : '' }}<br>
                                        {{ !empty($value->registered_by) ? '(' . sprintf('%09d', $value->registered_by) . ')' : '' }}
                                    </td>
                                    <td>{{ $value->placement_position }}</td>
                                    <td>{{ $value->activation_code_type }}</td>
                                    <td>{{ $value->ranking_lions_id }}</td>
                                    <td class="table-tools-column">
                                        <a href="{{ route('ibo.show', $value->id) }}">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                    </td>
                                    <td class="table-tools-column">
                                        <a href="{{ route('ibo.edit', $value->id) }}">
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
