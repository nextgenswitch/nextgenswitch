@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($plan->name) ? $plan->name : __('Plan') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['plans.plan.destroy', $plan->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('plans.plan.index') }}" class="btn btn-primary" title="{{ __('Show All Plan') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('plans.plan.create') }}" class="btn btn-success" title="{{ __('Create New Plan') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('plans.plan.edit', $plan->id ) }}" class="btn btn-primary" title="{{ __('Edit Plan') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Plan',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Plan.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $plan->name }}</dd>
            <dt>Duration</dt>
            <dd>{{ $plan->duration }}</dd>
            <dt>Price</dt>
            <dd>{{ $plan->price }}</dd>
            <dt>Created At</dt>
            <dd>{{ $plan->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $plan->updated_at }}</dd>
            <dt>Credit</dt>
            <dd>{{ $plan->credit }}</dd>

        </dl>

    </div>
</div>

@endsection
