@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <div class="pull-left">
                <h4 class="mb-3">{{ __('Create New Campaign') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('dialer_campaigns.dialer_campaign.index') }}" class="btn btn-primary" title="{{ __('Show All Campaign') }}">
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

            {!! Form::open([
                'route' => 'dialer_campaigns.dialer_campaign.store',
                'class' => 'form-horizontal',
                'name' => 'create_dialer_campaign_form',
                'id' => 'create_dialer_campaign_form',
                
                ])
            !!}

            @include ('dialer_campaigns.form', ['dialerCampaign' => null,])
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Add'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection


