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
                    <div>
                        <strong>Rank: {{ $data['ranking_lions'] }} (Level {{ $data['level'] }})</strong><br>
                        <strong>Total Rebates: {{ $data['rebates_total'] }}</strong>
                    </div>
                    <hr>
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
                                <td>
                                    <strong>
                                        {{ App\Ibo::find($data['ibos'][0][$data['user_ibo_id']]['ibo_id'])->firstname }} {{ App\Ibo::find($data['ibos'][0][$data['user_ibo_id']]['ibo_id'])->lastname }}<br>({{ sprintf('%09d', $data['ibos'][0][$data['user_ibo_id']]['ibo_id']) }})
                                    </strong>
                                </td>
                                <td><strong></strong></td>
                                <td><strong>{{ $data['ibos'][0][$data['user_ibo_id']]['total_purchase'] }}</strong></td>
                            </tr>
                            @foreach ($data['ibos_levels'] as $key => $value)
                                <?php $index = $key + 1 ?>
                                @foreach ($value as $key_ => $value_)
                                    <tr>
                                        <td><strong>{{ $index }}</strong></td>
                                        <td>
                                            <strong>
                                                {{ App\Ibo::find($value_['ibo_id'])->firstname }} {{ App\Ibo::find($value_['ibo_id'])->lastname }}<br>({{ sprintf('%09d', $value_['ibo_id']) }})
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>
                                                {{ App\Ibo::find($value_['placement_id'])->firstname }} {{ App\Ibo::find($value_['placement_id'])->lastname }}<br>({{ sprintf('%09d', $value_['placement_id']) }})
                                            </strong>
                                        </td>
                                        <td><strong>{{ $value_['total_purchase'] }}</strong></td>
                                    </tr>
                                    
                                    <?php $index = '' ?>
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
