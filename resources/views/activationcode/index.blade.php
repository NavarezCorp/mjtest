@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Activation Codes
                    <!--<a class="pull-right" href="{{-- route('activationtype.create') --}}">Add</a>-->
                </div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="" action="" id="activation-code-generator">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Activation Type</label>
                            <div class="col-md-6">
                                {{ Form::select('activation_type_id', $data['activation_types'], null, ['class'=>'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">How many character</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control" name="howmanychar" value="8" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">How many code</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control" name="howmanycode">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Generate</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <ul class="nav nav-tabs" id="code-tabs" role="tablist" style="margin-bottom:20px;">
                        <li role="presentation" class="{{ ($data['tab'] == 'not-yet-printed-codes') ? 'active' : '' }}">
                            <a href="#not-yet-printed-codes" id="not-yet-printed-codes-tab" role="tab" data-toggle="tab" aria-controls="not-yet-printed-codes" aria-expanded="true">Not yet printed</a>
                        </li>
                        <li role="presentation" class="{{ ($data['tab'] == 'all-codes') ? 'active' : '' }}">
                            <a href="#all-codes" role="tab" id="all-codes-tab" data-toggle="tab" aria-controls="all-codes" aria-expanded="false">All</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="codes-tab-content">
                        <div id="not-yet-printed-codes" class="tab-pane fade {{ ($data['tab'] == 'not-yet-printed-codes') ? 'active in' : '' }}" role="tabpanel" aria-labelledby="home-tab">
                            <a class="btn btn-primary" href="/activationcode/print_code/nypc" role="button" target="_blank">Print Codes</a>
                            <hr>
                            <div class="pull-right">{!! $data['not_yet_printed']->appends(['tab'=>'not-yet-printed-codes'])->links() !!}</div>
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
                                    @foreach ($data['not_yet_printed'] as $key => $value)
                                        <tr>
                                            <td>{{ $value->id }}</td>
                                            <td>{{ decrypt($value->code) }}</td>
                                            <td>{{ App\ActivationType::find($value->activation_type_id)->name }}</td>
                                            <td>{{ $value->created_at }}</td>
                                            <td>{{ App\User::find($value->created_by)->name }}</td>
                                            <td>{{ $value->used_by_ibo_id ? App\User::where('ibo_id', $value->used_by_ibo_id)->first()->name : '' }}</td>
                                            <td>{{ $value->datetime_used }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">{!! $data['not_yet_printed']->appends(['tab'=>'not-yet-printed-codes'])->links() !!}</div>
                        </div>
                        <div id="all-codes" class="tab-pane fade {{ ($data['tab'] == 'all-codes') ? 'active in' : '' }}" role="tabpanel" aria-labelledby="profile-tab">
                            <a class="btn btn-primary" href="/activationcode/print_code/all" role="button" target="_blank">Print Codes</a>
                            <hr>
                            <div class="pull-right">{!! $data['all']->appends(['tab'=>'all-codes'])->links() !!}</div>
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
                                    @foreach ($data['all'] as $key => $value)
                                        <tr>
                                            <td>{{ $value->id }}</td>
                                            <td>{{ decrypt($value->code) }}</td>
                                            <td>{{ App\ActivationType::find($value->activation_type_id)->name }}</td>
                                            <td>{{ $value->created_at }}</td>
                                            <td>{{ App\User::find($value->created_by)->name }}</td>
                                            <td>{{ $value->used_by_ibo_id ? App\User::where('ibo_id', $value->used_by_ibo_id)->first()->name : '' }}</td>
                                            <td>{{ $value->datetime_used }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">{!! $data['all']->appends(['tab'=>'all-codes'])->links() !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
