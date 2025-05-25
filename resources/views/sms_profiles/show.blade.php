@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($smsProfile->name) ? $smsProfile->name : __('Sms Profile') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['sms_profiles.sms_profile.destroy', $smsProfile->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('sms_profiles.sms_profile.index') }}" class="btn btn-primary" title="{{ __('Show All Sms Profile') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('sms_profiles.sms_profile.create') }}" class="btn btn-success" title="{{ __('Create New Sms Profile') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('sms_profiles.sms_profile.edit', $smsProfile->id ) }}" class="btn btn-primary" title="{{ __('Edit Sms Profile') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Sms Profile',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Sms Profile.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $smsProfile->created_at }}</dd>
            <dt>Default</dt>
            <dd>{{ $smsProfile->default }}</dd>
            <dt>Name</dt>
            <dd>{{ $smsProfile->name }}</dd>
            <dt>Options</dt>
            <dd>{{ $smsProfile->options }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($smsProfile->organization)->name }}</dd>
            <dt>Provider</dt>
            <dd>{{ $smsProfile->provider }}</dd>
            <dt>Status</dt>
            <dd>{{ $smsProfile->status }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $smsProfile->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
