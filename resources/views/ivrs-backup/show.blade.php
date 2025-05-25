@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($ivr->name) ? $ivr->name : __('Ivr') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['ivrs.ivr.destroy', $ivr->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('ivrs.ivr.index') }}" class="btn btn-primary" title="{{ __('Show All Ivr') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ivrs.ivr.create') }}" class="btn btn-success" title="{{ __('Create New Ivr') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ivrs.ivr.edit', $ivr->id ) }}" class="btn btn-primary" title="{{ __('Edit Ivr') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Ivr',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Ivr.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($ivr->organization)->id }}</dd>
            <dt>Name</dt>
            <dd>{{ $ivr->name }}</dd>
            <dt>Welcome Voice</dt>
            <dd>{{ $ivr->welcome_voice }}</dd>
            <dt>Created At</dt>
            <dd>{{ $ivr->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $ivr->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
