@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    <div class="row">
                        <div class="col-lg-3 pull-left">Flushout Details</div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th></th>
                            <th>Matched Date</th>
                            <th>IBO</th>
                            <th>Left IBO</th>
                            <th>Right IBO</th>
                            <th>Matched Bonus</th>
                        </thead>
                        <tbody>
                            @foreach ($data['matchings'] as $key => $value)
                                <tr>
                                    <td>#{{ $key + 1 }}</td>
                                    <td>{{ Carbon\Carbon::parse($value->datetime_matched)->setTimezone('Asia/Manila')->format('Y-m-d g:i:s A') }}</td>
                                    <td>
                                        <strong>{{ sprintf('%09d', $value->ibo_id) }}</strong><br/>
                                        {{ isset(App\Ibo::find($value->ibo_id)->firstname) ? App\Ibo::find($value->ibo_id)->firstname : '' }} 
                                        {{ isset(App\Ibo::find($value->ibo_id)->middlename) ? App\Ibo::find($value->ibo_id)->middlename : '' }} 
                                        {{ isset(App\Ibo::find($value->ibo_id)->lastname) ? App\Ibo::find($value->ibo_id)->lastname : '' }}
                                    </td>
                                    <td>{{ $value->left}}</td>
                                    <td>{{ $value->right }}</td>
                                    <td>{{ ($key <= 11) ? $data['matching_bonus_amount'] : 0.00 }}</td>
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
