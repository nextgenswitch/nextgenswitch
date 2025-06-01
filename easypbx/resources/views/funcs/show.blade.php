@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($func->name) ? $func->name : __('Func') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['funcs.func.destroy', $func->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('funcs.func.index') }}" class="btn btn-primary" title="{{ __('Show All Func') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('funcs.func.create') }}" class="btn btn-success" title="{{ __('Create New Func') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('funcs.func.edit', $func->id ) }}" class="btn btn-primary" title="{{ __('Edit Func') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Func',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Func.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $func->created_at }}</dd>
            <dt>Func</dt>
            <dd>{{ $func->func }}</dd>
            <dt>Func Type</dt>
            <dd>{{ $func->func_type }}</dd>
            <dt>Name</dt>
            <dd>{{ $func->name }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($func->organization)->id }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $func->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
