@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($extension->name) ? $extension->name : __('Extension') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['extensions.extension.destroy', $extension->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('extensions.extension.index') }}" class="btn btn-primary" title="{{ __('Show All Extension') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('extensions.extension.create') }}" class="btn btn-success" title="{{ __('Create New Extension') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('extensions.extension.edit', $extension->id ) }}" class="btn btn-primary" title="{{ __('Edit Extension') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Extension',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Extension.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($extension->organization)->id }}</dd>
            <dt>Sip User</dt>
            <dd>{{ optional($extension->sipUser)->id }}</dd>
            <dt>Name</dt>
            <dd>{{ $extension->name }}</dd>
            <dt>Extension</dt>
            <dd>{{ $extension->extension }}</dd>
            <dt>Created At</dt>
            <dd>{{ $extension->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $extension->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
