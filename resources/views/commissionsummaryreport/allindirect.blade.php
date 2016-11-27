@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    Indirect Commission ({{ $data['type'] }})
                    <span class="pull-right">
                        <strong class="pull-right">{{ $data['date_start'] }} - {{ $data['date_end'] }}</strong>
                    </span>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>IBO Name</th>
                            <th>Indirect Sponsor</th>
                        </thead>
                        <tbody>
                            @foreach ($data['commission'] as $key => $value)
                                <tr>
                                    <td><strong>{{ $value['ibo_name'] }}</strong></td>
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
