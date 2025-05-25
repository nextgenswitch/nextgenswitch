@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($organization->name) ? $organization->name : __('Organization') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['organizations.organization.destroy', $organization->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('organizations.organization.index') }}" class="btn btn-primary" title="{{ __('Show All Organization') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('organizations.organization.create') }}" class="btn btn-success" title="{{ __('Create New Organization') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('organizations.organization.edit', $organization->id ) }}" class="btn btn-primary" title="{{ __('Edit Organization') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Organization',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Organization.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Plan</dt>
            <dd>{{ optional($organization->plan)->name }}</dd>
            <dt>Name</dt>
            <dd>{{ $organization->name }}</dd>
            <dt>Domain</dt>
            <dd>{{ $organization->domain }}</dd>
            <dt>Contact No</dt>
            <dd>{{ $organization->contact_no }}</dd>
            <dt>Email</dt>
            <dd>{{ $organization->email }}</dd>
            <dt>Address</dt>
            <dd>{{ $organization->address }}</dd>
            <dt>Credit</dt>
            <dd>{{ $organization->credit }}</dd>
            <dt>Created At</dt>
            <dd>{{ $organization->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $organization->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
