@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($survey->name) ? $survey->name : __('Survey') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['surveys.survey.destroy', $survey->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('surveys.survey.index') }}" class="btn btn-primary" title="{{ __('Show All Survey') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('surveys.survey.create') }}" class="btn btn-success" title="{{ __('Create New Survey') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('surveys.survey.edit', $survey->id ) }}" class="btn btn-primary" title="{{ __('Edit Survey') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Survey',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Survey.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($survey->organization)->name }}</dd>
            <dt>Name</dt>
            <dd>{{ $survey->name }}</dd>
            <dt>Voice</dt>
            <dd>{{ optional($survey->voice)->id }}</dd>
            <dt>Type</dt>
            <dd>{{ $survey->type }}</dd>
            <dt>Keys</dt>
            <dd>{{ $survey->keys }}</dd>
            <dt>Function</dt>
            <dd>{{ optional($survey->function)->id }}</dd>
            <dt>Destination</dt>
            <dd>{{ optional($survey->destination)->id }}</dd>
            <dt>Created At</dt>
            <dd>{{ $survey->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $survey->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
