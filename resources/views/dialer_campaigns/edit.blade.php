@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-3">{{ !empty($dialerCampaign->name) ? $dialerCampaign->name : __('Campaign') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('dialer_campaigns.dialer_campaign.index') }}" class="btn btn-primary" title="{{ __('Show All  Campaign') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('dialer_campaigns.dialer_campaign.create') }}" class="btn btn-primary" title="{{ __('Create New  Campaign') }}">
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

            {!! Form::model($dialerCampaign, [
                'method' => 'PUT',
                'route' => ['dialer_campaigns.dialer_campaign.update', $dialerCampaign->id],
                'class' => 'form-horizontal',
                'name' => 'edit_dialer_campaign_form',
                'id' => 'edit_dialer_campaign_form',
                
            ]) !!}

            @include ('dialer_campaigns.form', ['dialerCampaign' => $dialerCampaign,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection