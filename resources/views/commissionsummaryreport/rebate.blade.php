@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    Commission Summary Report ({{ $data['type'] }})
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>Level</th>
                            <th>IBO ID</th>
                            <th>Placement ID</th>
                            <th>Total Purchase</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>User</strong></td>
                                <td><strong>{{ $data['ibos'][0]['ibo_id'] }}</strong></td>
                                <td><strong></strong></td>
                                <td><strong>{{ $data['ibos'][0]['total_purchase'] }}</strong></td>
                            </tr>
                            @foreach ($data['ibos_levels'] as $key => $value)
                                @foreach ($value as $key_ => $value_)
                                    <tr>
                                        <td><strong>{{ $key + 1 }}</strong></td>
                                        <td><strong>{{ sprintf('%09d', $value_['ibo_id']) }}</strong></td>
                                        <td><strong>{{ sprintf('%09d', $value_['placement_id']) }}</strong></td>
                                        <td><strong>{{ $value_['total_purchase'] }}</strong></td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
