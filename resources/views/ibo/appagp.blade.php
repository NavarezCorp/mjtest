@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6 pull-left" style="margin:7px 0;">Accumulated Personal Purchase (APP) / Accumulated Group Purchase (AGP)</div>
                        <div class="col-md-6 pull-right text-right">
                            <img class="ibo-search-loading" src="/images/loading.gif">
                            <button class="btn btn-primary update-app-agp" type="button">Update</button>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3 pull-left text-left">
                            <div class="input-group" style="margin:22px 0;">
                                <input type="text" class="form-control" placeholder="Search IBO by Lastname">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-9 pull-right text-right">{!! $data->links() !!}</div>
                    </div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>IBO ID</th>
                            <th style="text-align:right;">APP</th>
                            <th style="text-align:right;">AGP</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>
                                        <strong>{{ !empty($value->id) ? '(' . sprintf('%09d', $value->id) . ')' : '' }}</strong><br>
                                        {{ isset(App\Ibo::find($value->id)->firstname) ? ucwords(strtolower(App\Ibo::find($value->id)->firstname)) : '' }}
                                        {{ isset(App\Ibo::find($value->id)->middlename) ? ucwords(strtolower(App\Ibo::find($value->id)->middlename)) : '' }}
                                        {{ isset(App\Ibo::find($value->id)->lastname) ? ucwords(strtolower(App\Ibo::find($value->id)->lastname)) : '' }}
                                    </td>
                                    <td style="text-align:right;">{{ number_format($value->app, 2) }}</td>
                                    <td style="text-align:right;">{{ number_format($value->agp, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">{!! $data->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
