@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($script->name) ? $script->name : __('Script') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['scripts.script.destroy', $script->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('scripts.script.index') }}" class="btn btn-primary" title="{{ __('Show All Script') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('scripts.script.create') }}" class="btn btn-success" title="{{ __('Create New Script') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('scripts.script.edit', $script->id ) }}" class="btn btn-primary" title="{{ __('Edit Script') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Script',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Script.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Content</dt>
            <dd>{{ $script->content }}</dd>
            <dt>Created At</dt>
            <dd>{{ $script->created_at }}</dd>
            <dt>Name</dt>
            <dd>{{ $script->name }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($script->organization)->name }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $script->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
