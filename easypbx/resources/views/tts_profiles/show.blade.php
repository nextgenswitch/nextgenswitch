@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($ttsProfile->name) ? $ttsProfile->name : __('Tts Profile') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['tts_profiles.tts_profile.destroy', $ttsProfile->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('tts_profiles.tts_profile.index') }}" class="btn btn-primary" title="{{ __('Show All Tts Profile') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('tts_profiles.tts_profile.create') }}" class="btn btn-success" title="{{ __('Create New Tts Profile') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('tts_profiles.tts_profile.edit', $ttsProfile->id ) }}" class="btn btn-primary" title="{{ __('Edit Tts Profile') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Tts Profile',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Tts Profile.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $ttsProfile->created_at }}</dd>
            <dt>Language</dt>
            <dd>{{ $ttsProfile->language }}</dd>
            <dt>Model</dt>
            <dd>{{ $ttsProfile->model }}</dd>
            <dt>Name</dt>
            <dd>{{ $ttsProfile->name }}</dd>
            <dt>Neural</dt>
            <dd>{{ ($ttsProfile->neural) ? 'Yes' : 'No' }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $ttsProfile->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
