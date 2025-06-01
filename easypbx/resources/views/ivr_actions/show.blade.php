@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Ivr Action') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['ivr_actions.ivr_action.destroy', $ivrAction->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('ivr_actions.ivr_action.index') }}" class="btn btn-primary" title="{{ __('Show All Ivr Action') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ivr_actions.ivr_action.create') }}" class="btn btn-success" title="{{ __('Create New Ivr Action') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ivr_actions.ivr_action.edit', $ivrAction->id ) }}" class="btn btn-primary" title="{{ __('Edit Ivr Action') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Ivr Action',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Ivr Action.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($ivrAction->organization)->id }}</dd>
            <dt>Ivr</dt>
            <dd>{{ optional($ivrAction->ivr)->name }}</dd>
            <dt>Digit</dt>
            <dd>{{ $ivrAction->digit }}</dd>
            <dt>Destination</dt>
            <dd>{{ optional($ivrAction->destination)->id }}</dd>
            <dt>Function</dt>
            <dd>{{ optional($ivrAction->function)->id }}</dd>
            <dt>Created At</dt>
            <dd>{{ $ivrAction->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $ivrAction->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
