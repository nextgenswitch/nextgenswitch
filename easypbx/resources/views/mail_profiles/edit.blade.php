@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($mailProfile->name) ? $mailProfile->name : __('Email Profile') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('mail_profiles.mail_profile.index') }}" class="btn btn-primary" title="{{ __('Show All Email Profile') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('mail_profiles.mail_profile.create') }}" class="btn btn-primary" title="{{ __('Create New Email Profile') }}">
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

            {!! Form::model($mailProfile, [
                'method' => 'PUT',
                'route' => ['mail_profiles.mail_profile.update', $mailProfile->id],
                'class' => 'form-horizontal',
                'name' => 'edit_mail_profile_form',
                'id' => 'edit_mail_profile_form',
            ]) !!}

            @include ('mail_profiles.form', ['mailProfile' => $mailProfile])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection