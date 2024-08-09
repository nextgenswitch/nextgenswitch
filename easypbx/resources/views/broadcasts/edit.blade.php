@extends('layouts.app')

@section('title', __('Edit Broadcast'))

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($campaign->name) ? $campaign->name : __('Broadcast name') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('broadcasts.broadcast.index') }}" class="btn btn-primary" title="{{ __('Show all Broadcasts') }}">
                     <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('broadcasts.broadcast.create') }}" class="btn btn-primary" title="{{ 'Create new Broadcast' }}">
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

            {!! Form::model($campaign, [
                'method' => 'PUT',
                'route' => ['broadcasts.broadcast.update', $campaign->id],
                'class' => 'form-horizontal ajaxForm',
                'name' => 'edit_campaign_form',
                'id' => 'campaign_form',
                'path' => route('broadcasts.broadcast.destinations', 0)
                
            ]) !!}

            @include ('broadcasts.form', ['campaign' => $campaign,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit('Update Broadcast', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection