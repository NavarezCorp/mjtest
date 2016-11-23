@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Pickup Centers
                    <a class="pull-right" href="{{ route('pickupcenter.create') }}">Add</a>
                </div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <div class="pull-right">{!! $data['pickup_centers']->links() !!}</div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>ID</th>
                            <th>Country</th>
                            <th>City</th>
                            <th>Branch</th>
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($data['pickup_centers'] as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ App\Country::find($value->country_id)->name }}</td>
                                    <td>{{ App\City::find($value->city_id)->name }}</td>
                                    <td>{{ $value->branch }}</td>
                                    <td class="table-tools-column">
                                        <a href="{{ route('pickupcenter.show', $value->id) }}">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                    </td>
                                    <td class="table-tools-column">
                                        <a href="{{ route('pickupcenter.edit', $value->id) }}">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">{!! $data['pickup_centers']->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
