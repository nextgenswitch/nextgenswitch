@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($title) ? $title : __('Call Leg') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('call_legs.call_leg.index') }}" class="btn btn-primary" title="{{ __('Show All Call Leg') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('call_legs.call_leg.create') }}" class="btn btn-primary" title="{{ __('Create New Call Leg') }}">
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

            {!! Form::model($callLeg, [
                'method' => 'PUT',
                'route' => ['call_legs.call_leg.update', $callLeg->id],
                'class' => 'form-horizontal',
                'name' => 'edit_call_leg_form',
                'id' => 'edit_call_leg_form',
                
            ]) !!}

            @include ('call_legs.form', ['callLeg' => $callLeg,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection