@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($callParking->name) ? $callParking->name : __('Call Parking') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('call_parkings.call_parking.index') }}" class="btn btn-primary" title="{{ __('Show All Call Parking') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('call_parkings.call_parking.create') }}" class="btn btn-primary" title="{{ __('Create New Call Parking') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>

            </div>
        </div>

        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {!! Form::model($callParking, [
                'method' => 'PUT',
                'route' => ['call_parkings.call_parking.update', $callParking->id],
                'class' => 'form-horizontal',
                'name' => 'edit_call_parking_form',
                'id' => 'edit_call_parking_form',
                
            ]) !!}

            @include ('call_parkings.form', ['callParking' => $callParking,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection