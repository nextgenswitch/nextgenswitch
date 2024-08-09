@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($application->name) ? $application->name : __('Application') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['applications.application.destroy', $application->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('applications.application.index') }}" class="btn btn-primary" title="{{ __('Show All Application') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('applications.application.create') }}" class="btn btn-success" title="{{ __('Create New Application') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('applications.application.edit', $application->id ) }}" class="btn btn-primary" title="{{ __('Edit Application') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Application',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Application.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Code</dt>
            <dd>{{ $application->code }}</dd>
            <dt>Created At</dt>
            <dd>{{ $application->created_at }}</dd>
            <dt>Destination</dt>
            <dd>{{ optional($application->destination)->id }}</dd>
            <dt>Function</dt>
            <dd>{{ optional($application->function)->id }}</dd>
            <dt>Name</dt>
            <dd>{{ $application->name }}</dd>
            <dt>Status</dt>
            <dd>{{ $application->status }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $application->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
