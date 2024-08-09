@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($timeCondition->name) ? $timeCondition->name : __('Time Condition') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('time_conditions.time_condition.index') }}" class="btn btn-primary" title="{{ __('Show All Time Condition') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('time_conditions.time_condition.create') }}" class="btn btn-primary" title="{{ __('Create New Time Condition') }}">
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

            {!! Form::model($timeCondition, [
                'method' => 'PUT',
                'route' => ['time_conditions.time_condition.update', $timeCondition->id],
                'class' => 'form-horizontal ajaxForm',
                'name' => 'edit_time_condition_form',
                'id' => 'edit_time_condition_form',
                'path' => route('time_conditions.time_condition.destinations', 0),
                
            ]) !!}

            @include ('time_conditions.form', ['timeCondition' => $timeCondition,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection