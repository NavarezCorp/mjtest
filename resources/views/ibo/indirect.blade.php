@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    My Weekly Commission Summary Report
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>Date Start</th>
                            <th>Date End</th>
                            <th>Indirect Sponsor</th>
                        </thead>
                        <tbody>
                            @foreach ($data['commission'] as $key => $value)
                                <tr>
                                    <td><strong>{{ $value['date_start'] }}</strong></td>
                                    <td><strong>{{ $value['date_end'] }}</strong></td>
                                    <td><strong>{{ $value['indirect'] }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
