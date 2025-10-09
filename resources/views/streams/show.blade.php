@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($stream->name) ? $stream->name : __('Stream') }}</h4>
        </div>
        <div class="pull-right">
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['streams.stream.destroy', $stream->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('streams.stream.index') }}" class="btn btn-primary" title="{{ __('Show All Streams') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>
                    <a href="{{ route('streams.stream.create') }}" class="btn btn-success" title="{{ __('Create New Stream') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    <a href="{{ route('streams.stream.edit', $stream->id ) }}" class="btn btn-primary" title="{{ __('Edit Stream') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>
                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', [   'type'    => 'submit', 'class'   => 'btn btn-danger', 'title'   => 'Delete Stream', 'onclick' => "return confirm('Click Ok to delete Stream.')"] ) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>{{ __('Organization') }}</dt>
            <dd>{{ $stream->organization_id }}</dd>
            <dt>{{ __('Name') }}</dt>
            <dd>{{ $stream->name }}</dd>
            <dt>{{ __('WS URL') }}</dt>
            <dd>{{ $stream->ws_url }}</dd>
            <dt>{{ __('Prompt') }}</dt>
            <dd>{{ $stream->prompt }}</dd>
            <dt>{{ __('Forwarding Number') }}</dt>
            <dd>{{ $stream->forwarding_number }}</dd>
            <dt>{{ __('Email') }}</dt>
            <dd>{{ $stream->email }}</dd>
        </dl>
    </div>
</div>
@endsection
