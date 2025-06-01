@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($customFunc->name) ? $customFunc->name : __('Custom Func') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['custom_funcs.custom_func.destroy', $customFunc->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('custom_funcs.custom_func.index') }}" class="btn btn-primary" title="{{ __('Show All Custom Func') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('custom_funcs.custom_func.create') }}" class="btn btn-success" title="{{ __('Create New Custom Func') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('custom_funcs.custom_func.edit', $customFunc->id ) }}" class="btn btn-primary" title="{{ __('Edit Custom Func') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Custom Func',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Custom Func.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($customFunc->organization)->id }}</dd>
            <dt>Name</dt>
            <dd>{{ $customFunc->name }}</dd>
            <dt>Func Lang</dt>
            <dd>{{ $customFunc->func_lang }}</dd>
            <dt>Func Body</dt>
            <dd>{{ $customFunc->func_body }}</dd>
            <dt>Created At</dt>
            <dd>{{ $customFunc->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $customFunc->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
