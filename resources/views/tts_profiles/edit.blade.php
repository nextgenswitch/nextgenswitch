@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            

            <div class="pull-left">
                <h4 class="mb-5">{{ __("AI Provider") }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('tts_profiles.tts_profile.index') }}" class="btn btn-primary" title="{{ __('Show All Tts Profile') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
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

            {!! Form::model($ttsProfile, [
                'method' => 'PUT',
                'enctype' => 'multipart/form-data',
                'route' => ['tts_profiles.tts_profile.update', $ttsProfile->id],
                'class' => 'form-horizontal',
                'name' => 'edit_tts_profile_form',
                'id' => 'edit_tts_profile_form',
                
            ]) !!}

            @include ('tts_profiles.form', ['ttsProfile' => $ttsProfile,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection