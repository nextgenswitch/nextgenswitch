@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($timeGroup->name) ? $timeGroup->name : __('Time Group') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('time_groups.time_group.index') }}" class="btn btn-primary" title="{{ __('Show All Time Group') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('time_groups.time_group.create') }}" class="btn btn-primary" title="{{ __('Create New Time Group') }}">
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

            {!! Form::model($timeGroup, [
                'method' => 'PUT',
                'route' => ['time_groups.time_group.update', $timeGroup->id],
                'class' => 'form-horizontal',
                'name' => 'edit_time_group_form',
                'id' => 'edit_time_group_form',
                'schedules' => optional($timeGroup)->schedules
            ]) !!}

            @include ('time_groups.form', ['timeGroup' => $timeGroup,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection