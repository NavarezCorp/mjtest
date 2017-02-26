@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New member account information</div>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/ibo') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="cid" id="cid" value="">
                    <input type="hidden" name="activation_code_type" id="activation_code_type" value="">
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="background-color:#e7e7e7;">Personal information</div>
                            <div class="panel-body">
                                <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }} required">
                                    <label for="firstname" class="col-md-3 control-label">Firstname</label>
                                    <div class="col-md-6">
                                        <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>
                                        @if ($errors->has('firstname'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('firstname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('middlename') ? ' has-error' : '' }} required">
                                    <label for="middlename" class="col-md-3 control-label">Middlename</label>
                                    <div class="col-md-6">
                                        <input id="middlename" type="text" class="form-control" name="middlename" value="{{ old('middlename') }}" required>
                                        @if ($errors->has('middlename'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('middlename') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }} required">
                                    <label for="lastname" class="col-md-3 control-label">Lastname</label>
                                    <div class="col-md-6">
                                        <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>
                                        @if ($errors->has('lastname'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('lastname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-3 control-label">Email</label>
                                    <div class="col-md-6">
                                        <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }} required">
                                    <label for="gender" class="col-md-3 control-label">Gender</label>
                                    <div class="col-md-2">
                                        {{ Form::select('gender', $data['genders'], null, ['class'=>'form-control', 'placeholder'=>'']) }}
                                    </div>
                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }} required">
                                    <label for="birth_date" class="col-md-3 control-label">Birth Date</label>
                                    <div class="col-md-4">
                                        <input id="birth_date" type="text" class="form-control" name="birth_date" placeholder="mm/dd/yyyy" value="{{ old('birth_date') }}" required>
                                        @if ($errors->has('birth_date'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('birth_date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('marital_status') ? ' has-error' : '' }}">
                                    <label for="marital_status" class="col-md-3 control-label">Marital Status</label>
                                    <div class="col-md-4">
                                        {{ Form::select('marital_status', $data['marital_status'], null, ['class'=>'form-control', 'placeholder'=>'']) }}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('tin') ? ' has-error' : '' }}">
                                    <label for="tin" class="col-md-3 control-label">TIN</label>
                                    <div class="col-md-6">
                                        <input id="tin" type="text" class="form-control" name="tin" value="{{ old('tin') }}">
                                        @if ($errors->has('tin'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tin') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('sss') ? ' has-error' : '' }}">
                                    <label for="sss" class="col-md-3 control-label">SSS</label>
                                    <div class="col-md-6">
                                        <input id="sss" type="text" class="form-control" name="sss" value="{{ old('sss') }}">
                                        @if ($errors->has('sss'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('sss') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }} required">
                                    <label for="address" class="col-md-3 control-label">Address</label>
                                    <div class="col-md-9">
                                        <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required>
                                        @if ($errors->has('address'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }} required">
                                    <label for="city" class="col-md-3 control-label">City</label>
                                    <div class="col-md-6">
                                        <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>
                                        @if ($errors->has('city'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('city') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('province') ? ' has-error' : '' }} required">
                                    <label for="province" class="col-md-3 control-label">Province</label>
                                    <div class="col-md-6">
                                        <input id="province" type="text" class="form-control" name="province" value="{{ old('province') }}" required>
                                        @if ($errors->has('province'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('province') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }} required">
                                    <label for="contact_no" class="col-md-3 control-label">Contact #</label>
                                    <div class="col-md-6">
                                        <input id="contact_no" type="text" class="form-control" name="contact_no" value="{{ old('contact_no') }}" required>
                                        @if ($errors->has('contact_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('contact_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" style="background-color:#e7e7e7;">SFI information</div>
                            <div class="panel-body">
                                <div class="form-group{{ $errors->has('sponsor_id') ? ' has-error' : '' }} required">
                                    <label for="sponsor_id" class="col-md-3 control-label">Sponsor ID</label>
                                    <div class="col-md-6">
                                        <input id="sponsor_id" type="text" class="form-control" name="sponsor_id" value="{{ old('sponsor_id') }}" required>
                                        @if ($errors->has('sponsor_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('sponsor_id') }}</strong>
                                            </span>
                                        @endif
                                        <span class="help-block sponsor_id-help-block" style="display:none;"></span>
                                    </div>
                                    <button type="button" class="btn btn-default ibo-info-search" data-id="sponsor_id">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="form-group{{ $errors->has('placement_id') ? ' has-error' : '' }} required">
                                    <label for="placement_id" class="col-md-3 control-label">Placement ID</label>
                                    <div class="col-md-6">
                                        <input id="placement_id" type="text" class="form-control" name="placement_id" value="{{ $data['placement_id'] ? sprintf('%09d', $data['placement_id']) : '' }}" required>
                                        @if ($errors->has('placement_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('placement_id') }}</strong>
                                            </span>
                                        @endif
                                        <span class="help-block placement_id-help-block" style="display:none;"></span>
                                    </div>
                                    <button type="button" class="btn btn-default ibo-info-search" data-id="placement_id">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="form-group{{ $errors->has('placement_position') ? ' has-error' : '' }} required">
                                    <label for="placement_position" class="col-md-3 control-label">Placement position</label>
                                    <div class="col-md-2">
                                        {{ Form::select('placement_position', ['L'=>'Left', 'R' => 'Right'], $data['placement_position'], ['class'=>'form-control', 'placeholder'=>'', 'required']) }}
                                        @if ($errors->has('placement_position'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('placement_position') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('activation_code') ? ' has-error' : '' }} required">
                                    <label for="placement_id" class="col-md-3 control-label">Activation Code</label>
                                    <div class="col-md-5">
                                        <input id="activation_code" type="text" class="form-control" name="activation_code" value="{{ old('activation_code') }}" style="text-transform:uppercase" required>
                                        @if ($errors->has('activation_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('activation_code') }}</strong>
                                            </span>
                                        @endif
                                        <span class="help-block code-help-block" style="display:none;"></span>
                                    </div>
                                    <button type="button" class="btn btn-default code-search">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="form-group{{ $errors->has('pickup_center') ? ' has-error' : '' }} required">
                                    <label for="pickup_center" class="col-md-3 control-label">Pickup center</label>
                                    <div class="col-md-6">
                                        {{ Form::select('pickup_center', $data['pickup_centers'], null, ['class'=>'form-control', 'placeholder'=>'']) }}
                                        @if ($errors->has('pickup_center'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('pickup_center') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" style="background-color:#e7e7e7;">Bank information</div>
                            <div class="panel-body">
                                <div class="form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                                    <label for="bank_name" class="col-md-3 control-label">Bank name</label>
                                    <div class="col-md-6">
                                        {{ Form::select('bank_id', $data['banks'], null, ['class'=>'form-control', 'placeholder'=>'Not applicable']) }}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('account_no') ? ' has-error' : '' }}">
                                    <label for="account_no" class="col-md-3 control-label">Account #</label>
                                    <div class="col-md-6">
                                        <input id="account_no" type="text" class="form-control" name="account_no" value="{{ old('account_no') }}">
                                        @if ($errors->has('account_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('account_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary register-button" disabled>
                                Register
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
