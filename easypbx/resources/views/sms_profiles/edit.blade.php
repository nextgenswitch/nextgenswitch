@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($smsProfile->name) ? $smsProfile->name : __('Sms Profile') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('sms_profiles.sms_profile.index') }}" class="btn btn-primary" title="{{ __('Show All Sms Profile') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('sms_profiles.sms_profile.create') }}" class="btn btn-primary" title="{{ __('Create New Sms Profile') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>

            </div>
        </div>

        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {!! Form::model($smsProfile, [
                'method' => 'PUT',
                'route' => ['sms_profiles.sms_profile.update', $smsProfile->id],
                'class' => 'form-horizontal',
                'name' => 'edit_sms_profile_form',
                'id' => 'edit_sms_profile_form',
                
            ]) !!}

            @include ('sms_profiles.form', ['smsProfile' => $smsProfile,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection