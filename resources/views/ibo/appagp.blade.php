@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Accumulated Personal Purchase (APP) / Accumulated Group Purchase (AGP)
                </div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <div class="pull-right">{!! $data->links() !!}</div>
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
                                        {{ isset(App\Ibo::find($value->id)->firstname) ? App\Ibo::find($value->id)->firstname : '' }}
                                        {{ isset(App\Ibo::find($value->id)->middlename) ? App\Ibo::find($value->id)->middlename : '' }}
                                        {{ isset(App\Ibo::find($value->id)->lastname) ? App\Ibo::find($value->id)->lastname : '' }}<br>
                                        {{ !empty($value->id) ? '(' . sprintf('%09d', $value->id) . ')' : '' }}
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
