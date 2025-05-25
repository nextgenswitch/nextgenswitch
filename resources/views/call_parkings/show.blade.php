@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($callParking->name) ? $callParking->name : __('Call Parking') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['call_parkings.call_parking.destroy', $callParking->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('call_parkings.call_parking.index') }}" class="btn btn-primary" title="{{ __('Show All Call Parking') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('call_parkings.call_parking.create') }}" class="btn btn-success" title="{{ __('Create New Call Parking') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('call_parkings.call_parking.edit', $callParking->id ) }}" class="btn btn-primary" title="{{ __('Edit Call Parking') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Call Parking',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Call Parking.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $callParking->name }}</dd>
            <dt>Extension No</dt>
            <dd>{{ $callParking->extension_no }}</dd>
            <dt>No Of Slot</dt>
            <dd>{{ $callParking->no_of_slot }}</dd>
            <dt>Music On Hold</dt>
            <dd>{{ $callParking->music_on_hold }}</dd>
            <dt>Timeout</dt>
            <dd>{{ $callParking->timeout }}</dd>
            <dt>Function</dt>
            <dd>{{ optional($callParking->function)->id }}</dd>
            <dt>Destination</dt>
            <dd>{{ optional($callParking->destination)->id }}</dd>
            <dt>Created At</dt>
            <dd>{{ $callParking->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $callParking->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
