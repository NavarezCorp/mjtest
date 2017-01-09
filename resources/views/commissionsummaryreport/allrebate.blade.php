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
                    <div class="pull-right" style="margin-bottom:40px;">
                        <span class="pull-right" style="position:relative; top:-7px;">
                            <span class="pull-left" style="margin-right: 5px; position: relative; top: 6px;">Year:</span>
                            <select id="rebates-year" style="width:auto;" class="form-control selectWidth pull-right">
                                @for ($i = 2016; $i <= $data['current_year']; $i++)
                                    <option value="{{$i}}" {{ ($data['selected_year'] == $i) ? 'selected' : '' }}>{{$i}}</option>
                                @endfor
                            </select>
                        </span>
                        <span class="pull-right" style="margin-right:20px; position:relative; top:-7px;">
                            <span class="pull-left" style="margin-right: 5px; position: relative; top: 6px;">Month:</span>
                            <select id="rebates-week" style="width:auto;" class="form-control selectWidth pull-right">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{$i}}" {{ ($data['selected_month'] == $i) ? 'selected' : '' }}>{{ $data['months'][$i]}}</option>
                                @endfor
                            </select>
                        </span>
                    </div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>IBO</th>
                            <th>Ranking Lion</th>
                            <th>Level</th>
                            <th>Rebate</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @if (isset($data['rebate']))
                                @foreach ($data['rebate'] as $key => $value)
                                    @if ($value['rebate'] > 0)
                                        <tr>
                                            <td>
                                                <strong>
                                                    {{ App\Ibo::find($value['ibo_id'])->firstname }} {{ App\Ibo::find($value['ibo_id'])->lastname }}<br>({{ sprintf('%09d', $value['ibo_id']) }})
                                                </strong>
                                            </td>
                                            <td><strong>{{ $value['ranking_lions'] }}</strong></td>
                                            <td><strong>{{ $value['level'] }}</strong></td>
                                            <td><strong>{{ $value['rebate'] }}</strong></td>
                                            <td style="width:70px; text-align: center;">
                                                <strong>
                                                    <a href="{{ $value['ibo_id'] }}?type=myrebate&month={{ $data['selected_month'] }}&year={{ $data['selected_year']}}" target="_blank">details</a>
                                                </strong>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
