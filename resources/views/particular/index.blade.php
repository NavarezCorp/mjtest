@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    <div class="row">
                        <div class="col-md-3">Particular</div>
                        <div class="col-md-9">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control particular-ibo-id" placeholder="IBO ID" value="{{ $data['ibo_id'] }}">
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control particular-from-date" placeholder="From" value="{{ $data['from'] }}">
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control particular-to-date" placeholder="To" value="{{ $data['to'] }}">
                            </div>
                            <div class="col-xs-1">
                                <div class="row">
                                    <button class="btn btn-primary particular-fetch" type="button"><strong>Fetch</strong></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    Name: <span>{{ $data['name'] }}</span><br/>
                    IBO ID: <span>{{ $data['ibo_id'] }}</span><br/>
                    Date Range: <span>{{ $data['from'] . ' - ' . $data['to'] }}</span><br/>
                    <table class="table table-striped table-hover table-condensed table-bordered particular-table" style="margin-top:50px;">
                        <thead>
                            <tr>
                                <th class="no-border-tl"></th>
                                <th colspan="3">Left</th>
                                <th colspan="3">Right</th>
                                <th colspan="3" class="no-border-tr"></th>
                            </tr>
                            <tr>
                                <th style="text-align:left;">Date</th>
                                <th>OW</th>
                                <th>NW</th>
                                <th>NE</th>
                                <th>NE</th>
                                <th>NW</th>
                                <th>OW</th>
                                <th>Match</th>
                                <th>Fifth</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data['particulars'] as $value)
                                    <tr>
                                        <td style="text-align:left;">{{ $value['date'] }}</td>
                                        <td>{{ $value['left']['ow'] }}</td>
                                        <td>{{ $value['left']['nw'] }}</td>
                                        <td>{{ $value['left']['ne'] }}</td>
                                        <td>{{ $value['right']['ne'] }}</td>
                                        <td>{{ $value['right']['nw'] }}</td>
                                        <td>{{ $value['right']['ow'] }}</td>
                                        <td>{{ $value['match'] }}</td>
                                        <td>{{ $value['fifth'] }}</td>
                                        <td>{{ $value['match'] + $value['fifth'] }}</td>
                                    </tr>
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
