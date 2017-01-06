@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    Commission Summary Report ({{ $data['type'] }})
                    <span class="pull-right">
                        <strong class="pull-right">{{ $data['date_start'] }} - {{ $data['date_end'] }}</strong>
                    </span>
                </div>
                <div class="panel-body">
                    <div class="pull-right" style="margin-bottom:40px;">
                        <span class="pull-right" style="position:relative; top:-7px;">
                            <span class="pull-left" style="margin-right: 5px; position: relative; top: 6px;">Year:</span>
                            <select id="selectYear" style="width:auto;" class="form-control selectWidth pull-right">
                                @for ($i = 2016; $i <= $data['current_year']; $i++)
                                    <option value="{{$i}}" {{ ($data['selected_year'] == $i) ? 'selected' : '' }}>{{$i}}</option>
                                @endfor
                            </select>
                        </span>
                        <span class="pull-right" style="margin-right:20px; position:relative; top:-7px;">
                            <span class="pull-left" style="margin-right: 5px; position: relative; top: 6px;">Week:</span>
                            <select id="selectWeek" style="width:auto;" class="form-control selectWidth pull-right">
                                @for ($i = 1; $i <= $data['current_week_no']; $i++)
                                    <option value="{{$i}}" {{ ($data['selected_week'] == $i) ? 'selected' : '' }}>{{$i}}</option>
                                @endfor
                            </select>
                        </span>
                    </div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>IBO Name</th>
                            <th>Direct Sponsor</th>
                            <!--<th>Indirect Sponsor</th>-->
                            <th>Matching Bonus</th>
                            <th>Fifth Pairs</th>
                            <th>Net Commission</th>
                        </thead>
                        <tbody>
                            @foreach ($data['commission'] as $key => $value)
                                @if (isset($value))
                                    <tr style="background-color:green; color: white; font-weight: bold;">
                                        <td colspan="5">{{ isset(App\PickupCenter::find($key)->branch) ? App\PickupCenter::find($key)->branch : 'Davao City' }}</td>
                                    </tr>
                                    @foreach ($value as $val)
                                        @if (($val['direct'] > 0) || ($val['fifth_pair'] > 0) || ($val['matching'] > 0))
                                            <tr>
                                                <td><strong>{{ $val['ibo_name'] }}</strong></td>
                                                <td><strong>{{ $val['direct'] }}</strong></td>
                                                <td><strong>{{ $val['matching'] }}</strong></td>
                                                <td><strong>{{ $val['fifth_pairs'] }}</strong></td>
                                                <td><strong>{{ $val['net_commission'] }}</strong></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr><td colspan="5"></td></tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
