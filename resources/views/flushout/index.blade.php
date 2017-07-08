@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    <div class="row">
                        <div class="col-lg-3 pull-left">Flushout</div>
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
                    <div class="pull-right" style="margin-bottom:40px;">
                        <span class="pull-right" style="position:relative; top:-7px;">
                            
                        </span>
                    </div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th class="col-sm-3">Matched Date</th>
                            <th class="col-sm-3">IBO</th>
                            <th class="col-sm-3">Left IBO</th>
                            <th class="col-sm-3">Right IBO</th>
                            <th class="col-sm-1 text-right">Details</th>
                        </thead>
                        <tbody>
                            @foreach ($data['matchings'] as $key => $value)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($value->datetime_matched)->setTimezone('Asia/Manila')->format('Y-m-d g:i:s A') }}</td>
                                    <td>{{ $value->ibo_id }}</td>
                                    <td>{{ $value->left }}</td>
                                    <td>{{ $value->right }}</td>
                                    <td><a href="#">Details</a></td>
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
