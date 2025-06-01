@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($extensionGroup->name) ? $extensionGroup->name : __('Extension Group') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['extension_groups.extension_group.destroy', $extensionGroup->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('extension_groups.extension_group.index') }}" class="btn btn-primary" title="{{ __('Show All Extension Group') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('extension_groups.extension_group.create') }}" class="btn btn-success" title="{{ __('Create New Extension Group') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('extension_groups.extension_group.edit', $extensionGroup->id ) }}" class="btn btn-primary" title="{{ __('Edit Extension Group') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Extension Group',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Extension Group.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($extensionGroup->organization)->id }}</dd>
            <dt>Name</dt>
            <dd>{{ $extensionGroup->name }}</dd>
            <dt>Extension</dt>
            <dd>{{ optional($extensionGroup->extension)->name }}</dd>
            <dt>Algorithm</dt>
            <dd>{{ $extensionGroup->algorithm }}</dd>
            <dt>Created At</dt>
            <dd>{{ $extensionGroup->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $extensionGroup->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
