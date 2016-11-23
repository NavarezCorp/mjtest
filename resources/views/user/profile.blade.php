@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New member account information</div>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/' . Auth::user()->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="background-color:#e7e7e7;">Personal information</div>
                            <div class="panel-body">
                                <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                    <label for="firstname" class="col-md-3 control-label">Firstname</label>
                                    <div class="col-md-6">
                                        <input id="firstname" type="text" class="form-control" name="firstname" value="{{ $data['ibo']['firstname'] }}" disabled>
                                        @if ($errors->has('firstname'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('firstname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('middlename') ? ' has-error' : '' }}">
                                    <label for="middlename" class="col-md-3 control-label">Middlename</label>
                                    <div class="col-md-6">
                                        <input id="middlename" type="text" class="form-control" name="middlename" value="{{ $data['ibo']['middlename'] }}" disabled>
                                        @if ($errors->has('middlename'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('middlename') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                                    <label for="lastname" class="col-md-3 control-label">Lastname</label>
                                    <div class="col-md-6">
                                        <input id="lastname" type="text" class="form-control" name="lastname" value="{{ $data['ibo']['lastname'] }}" disabled>
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
                                        <input id="email" type="text" class="form-control" name="email" value="{{ $data['ibo']['email'] }}">
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
                                        {{ Form::select('gender_id', $data['genders'], $data['ibo']['gender_id'], ['class'=>'form-control', 'placeholder'=>'']) }}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }} required">
                                    <label for="birth_date" class="col-md-3 control-label">Birth Date</label>
                                    <div class="col-md-4">
                                        <input id="birth_date" type="text" class="form-control" name="birth_date" placeholder="mm/dd/yyyy" value="{{ $data['ibo']['birth_date'] }}" required>
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
                                        {{ Form::select('marital_status_id', $data['marital_status'], $data['ibo']['marital_status_id'], ['class'=>'form-control', 'placeholder'=>'']) }}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('tin') ? ' has-error' : '' }}">
                                    <label for="tin" class="col-md-3 control-label">TIN</label>
                                    <div class="col-md-6">
                                        <input id="tin" type="text" class="form-control" name="tin" value="{{ $data['ibo']['tin'] }}">
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
                                        <input id="sss" type="text" class="form-control" name="sss" value="{{ $data['ibo']['sss'] }}">
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
                                        <input id="address" type="text" class="form-control" name="address" value="{{ $data['ibo']['address'] }}" required>
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
                                        <input id="city" type="text" class="form-control" name="city" value="{{ $data['ibo']['city'] }}" required>
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
                                        <input id="province" type="text" class="form-control" name="province" value="{{ $data['ibo']['province'] }}" required>
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
                                        <input id="contact_no" type="text" class="form-control" name="contact_no" value="{{ $data['ibo']['contact_no'] }}" required>
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
                                <div class="form-group{{ $errors->has('sponsor_id') ? ' has-error' : '' }}">
                                    <label for="sponsor_id" class="col-md-3 control-label">Sponsor ID</label>
                                    <div class="col-md-6">
                                        <input id="sponsor_id" type="text" class="form-control" name="sponsor_id" value="{{ $data['ibo']['sponsor_id'] }}" disabled>
                                        @if ($errors->has('sponsor_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('sponsor_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('placement_id') ? ' has-error' : '' }}">
                                    <label for="placement_id" class="col-md-3 control-label">Placement ID</label>
                                    <div class="col-md-6">
                                        <input id="placement_id" type="text" class="form-control" name="placement_id" value="{{ $data['ibo']['placement_id'] ? sprintf('%09d', $data['ibo']['placement_id']) : '' }}" disabled>
                                        @if ($errors->has('placement_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('placement_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('placement_position') ? ' has-error' : '' }}">
                                    <label for="placement_position" class="col-md-3 control-label">Placement position</label>
                                    <div class="col-md-6">
                                        <input id="placement_position" type="text" class="form-control" name="placement_position" value="{{ $data['ibo']['placement_position'] ? $data['ibo']['placement_position'] : '' }}" disabled>
                                        @if ($errors->has('placement_position'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('placement_position') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('activation_code') ? ' has-error' : '' }}">
                                    <label for="placement_id" class="col-md-3 control-label">Activation Code</label>
                                    <div class="col-md-5">
                                        <input id="activation_code" type="text" class="form-control" name="activation_code" value="{{ $data['ibo']['activation_code'] }}" style="text-transform:uppercase" disabled>
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
                                        {{ Form::select('pickup_center_id', $data['pickup_centers'], $data['ibo']['pickup_center_id'], ['class'=>'form-control', 'placeholder'=>'']) }}
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
                                        {{ Form::select('bank_id', $data['banks'], $data['ibo']['bank_id'], ['class'=>'form-control', 'placeholder'=>'Not applicable']) }}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('account_no') ? ' has-error' : '' }}">
                                    <label for="account_no" class="col-md-3 control-label">Account #</label>
                                    <div class="col-md-6">
                                        <input id="account_no" type="text" class="form-control" name="account_no" value="{{ $data['ibo']['account_no'] }}">
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
                        <div class="col-md-6 col-md-offset-3">
                            <button type="submit" class="btn btn-primary register-button">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
