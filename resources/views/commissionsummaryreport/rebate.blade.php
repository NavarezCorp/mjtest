@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    Commission Summary Report ({{ $data['type'] }})
                    <div class="pull-right" style="margin-bottom:40px;">
                        <input type="hidden" id="myrebates-ibo-id" value="{{$data['ibo_id']}}">
                        <span class="pull-right" style="position:relative; top:-7px;">
                            <span class="pull-left" style="margin-right: 5px; position: relative; top: 6px;">Year:</span>
                            <select id="myrebates-year" style="width:auto;" class="form-control selectWidth pull-right">
                                @for ($i = 2016; $i <= $data['current_year']; $i++)
                                    <option value="{{$i}}" {{ ($data['selected_year'] == $i) ? 'selected' : '' }}>{{$i}}</option>
                                @endfor
                            </select>
                        </span>
                        <span class="pull-right" style="margin-right:20px; position:relative; top:-7px;">
                            <span class="pull-left" style="margin-right: 5px; position: relative; top: 6px;">Month:</span>
                            <select id="myrebates-week" style="width:auto;" class="form-control selectWidth pull-right">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{$i}}" {{ ($data['selected_month'] == $i) ? 'selected' : '' }}>{{ $data['months'][$i]}}</option>
                                @endfor
                            </select>
                        </span>
                    </div>
                </div>
                <div class="panel-body">
                    <div>
                        <strong>Rank: {{ $data['ranking_lions'] }} (Level {{ $data['level'] }})</strong><br>
                        <strong>Total Rebates: {{ $data['rebate']['rebates_total'] }}</strong><br>
                        <strong>Accumulated Personal Purchase (APP): Php {{ number_format($data['app'], 2) }}</strong><br>
                        <strong>Accumulated Group Purchase (AGP): Php {{ number_format($data['agp'], 2) }}</strong><br>
                        <strong>Accumulated Direct Sponsor Commission (ADSC): Php {{ number_format($data['adsc'], 2) }}</strong><br>
                        <strong>Accumulated Indirect Sponsor Commission (AISC): Php {{ number_format($data['aisc'], 2) }}</strong><br>
                        <strong>Accumulated Matching Bonus (AMB): Php {{ number_format(0, 2) }}</strong><br>
                    </div>
                    <hr>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>Level</th>
                            <th>IBO ID</th>
                            <th>Sponsor ID</th>
                            <th>Placement ID</th>
                            <th>Total Purchase</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>User</strong></td>
                                <td>
                                    <strong>
                                        {{ App\Ibo::find($data['rebate']['ibos'][0][$data['rebate']['user_ibo_id']]['ibo_id'])->firstname }} {{ App\Ibo::find($data['rebate']['ibos'][0][$data['rebate']['user_ibo_id']]['ibo_id'])->lastname }}<br>({{ sprintf('%09d', $data['rebate']['ibos'][0][$data['rebate']['user_ibo_id']]['ibo_id']) }})
                                    </strong>
                                </td>
                                <td><strong></strong></td>
                                <td><strong></strong></td>
                                <td><strong>{{ $data['rebate']['ibos'][0][$data['rebate']['user_ibo_id']]['total_purchase'] }}</strong></td>
                            </tr>
                            @foreach ($data['rebate']['ibos_levels'] as $key => $value)
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
                                                {{ App\Ibo::find($value_['sponsor_id'])->firstname }} {{ App\Ibo::find($value_['sponsor_id'])->lastname }}<br>({{ sprintf('%09d', $value_['sponsor_id']) }})
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
