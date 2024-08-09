@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($lead->name) ? $lead->name : __('Lead') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['leads.lead.destroy', $lead->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('leads.lead.index') }}" class="btn btn-primary" title="{{ __('Show All Lead') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('leads.lead.create') }}" class="btn btn-success" title="{{ __('Create New Lead') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('leads.lead.edit', $lead->id ) }}" class="btn btn-primary" title="{{ __('Edit Lead') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Lead',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Lead.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($lead->organization)->name }}</dd>
            <dt>Name</dt>
            <dd>{{ $lead->name }}</dd>
            <dt>Designation</dt>
            <dd>{{ $lead->designation }}</dd>
            <dt>Phone</dt>
            <dd>{{ $lead->phone }}</dd>
            <dt>Email</dt>
            <dd>{{ $lead->email }}</dd>
            <dt>Website</dt>
            <dd>{{ $lead->website }}</dd>
            <dt>Company</dt>
            <dd>{{ $lead->company }}</dd>
            <dt>Address</dt>
            <dd>{{ $lead->address }}</dd>
            <dt>Source</dt>
            <dd>{{ $lead->source }}</dd>
            <dt>Notes</dt>
            <dd>{{ $lead->notes }}</dd>
            <dt>Status</dt>
            <dd>{{ $lead->status }}</dd>
            <dt>Created At</dt>
            <dd>{{ $lead->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $lead->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
