@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($aiBot->name) ? $aiBot->name : __('AI Assistant') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['ai_bots.ai_bot.destroy', $aiBot->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('ai_bots.ai_bot.index') }}" class="btn btn-primary" title="{{ __('Show All AI Assistant') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ai_bots.ai_bot.create') }}" class="btn btn-success" title="{{ __('Create New AI Assistant') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ai_bots.ai_bot.edit', $aiBot->id ) }}" class="btn btn-primary" title="{{ __('Edit AI Assistant') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete AI Bot',
                            'onclick' => "return confirm(" .  __('Click Ok to delete AI Bot.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $aiBot->name }}</dd>
            <dt>Provider</dt>
            <dd>{{ $aiBot->provider }}</dd>
            <dt>Api Key</dt>
            <dd>{{ $aiBot->api_key }}</dd>
            <dt>Api Endpoint</dt>
            <dd>{{ $aiBot->api_endpoint }}</dd>
            <dt>Model</dt>
            <dd>{{ $aiBot->model }}</dd>
            <dt>Resource</dt>
            <dd>{{ $aiBot->resource }}</dd>
            <dt>Created At</dt>
            <dd>{{ $aiBot->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $aiBot->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
