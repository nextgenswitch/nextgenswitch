@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Flow') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['flows.flow.destroy', $flow->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('flows.flow.index') }}" class="btn btn-primary" title="{{ __('Show All Flow') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('flows.flow.create') }}" class="btn btn-success" title="{{ __('Create New Flow') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('flows.flow.edit', $flow->id ) }}" class="btn btn-primary" title="{{ __('Edit Flow') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Flow',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Flow.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $flow->created_at }}</dd>
            <dt>Match Action</dt>
            <dd>{{ optional($flow->matchAction)->id }}</dd>
            <dt>Match Type</dt>
            <dd>{{ $flow->match_type }}</dd>
            <dt>Match Value</dt>
            <dd>{{ $flow->match_value }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($flow->organization)->name }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $flow->updated_at }}</dd>
            <dt>Voice File</dt>
            <dd>{{ $flow->voice_file }}</dd>

        </dl>

    </div>
</div>

@endsection
