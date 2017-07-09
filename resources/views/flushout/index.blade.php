@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    <div class="row">
                        <div class="col-lg-3 pull-left">Flushout</div>
                        <div class="col-lg-6 text-right" style="padding-top:8px;">
                            <label class="row">Matched Date</label>
                        </div>
                        <div class="col-lg-3 pull-right">
                            <div class="input-group">
                                <input type="text" class="form-control flushout-date" placeholder="Date" value="{{ $data['date'] }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary flushout-fetch" type="button"><strong>Fetch</strong></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <!--<th class="col-sm-3">Matched Date</th>-->
                            <th>Details</th>
                            <th>IBO</th>
                            <th>Flushout</th>
                            <th>Matched Count</th>
                            <th>Matched Bonus</th>
                        </thead>
                        <tbody>
                            @foreach ($data['matchings'] as $key => $value)
                                <tr>
                                    <!--<td>{{ Carbon\Carbon::parse($data['selected_date'])->setTimezone('Asia/Manila')->format('Y-m-d g:i:s A') }}</td>-->
                                    <td><a href="#">Details</a></td>
                                    <td>
                                        <strong>{{ sprintf('%09d', $value->ibo_id) }}</strong><br/>
                                        {{ isset(App\Ibo::find($value->ibo_id)->firstname) ? App\Ibo::find($value->ibo_id)->firstname : '' }} 
                                        {{ isset(App\Ibo::find($value->ibo_id)->middlename) ? App\Ibo::find($value->ibo_id)->middlename : '' }} 
                                        {{ isset(App\Ibo::find($value->ibo_id)->lastname) ? App\Ibo::find($value->ibo_id)->lastname : '' }}
                                    </td>
                                    <td>{{ ($value->matched_count >= 12) ? 'Yes' : 'No' }}</td>
                                    <td>{{ ($value->matched_count >= 12) ? 12 : $value->matched_count }}</td>
                                    <td>{{ ($value->matched_count * $data["matching_bonus_amount"]) }}</td>
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
