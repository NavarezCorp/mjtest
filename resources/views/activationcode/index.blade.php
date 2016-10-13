@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Activation Types
                    <!--<a class="pull-right" href="{{-- route('activationtype.create') --}}">Add</a>-->
                </div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="" action="">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Activation Type</label>
                            <div class="col-md-6">
                                {{ Form::select('activation_type_id', $data['activation_types'], null, ['class'=>'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Length</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="length">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">How many code</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="howmanycode">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="pull-right">{!! $data['paginate']->links() !!}</div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Activation Type</th>
                            <th>DateTime Created</th>
                            <th>Created By</th>
                            <th>Used By</th>
                            <th>DateTime Used</th>
                        </thead>
                        <tbody>
                            @foreach ($data['paginate'] as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->code }}</td>
                                    <td>{{ $value->activation_type_id }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>{{ $value->created_by }}</td>
                                    <td>{{ $value->used_by_ibo_id }}</td>
                                    <td>{{ $value->datetime_used }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">{!! $data['paginate']->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
