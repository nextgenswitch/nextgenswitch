@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($title) ? $title : __('Flow Action') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('flow_actions.flow_action.index') }}" class="btn btn-primary" title="{{ __('Show All Flow Action') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('flow_actions.flow_action.create') }}" class="btn btn-primary" title="{{ __('Create New Flow Action') }}">
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

            {!! Form::model($flowAction, [
                'method' => 'PUT',
                'route' => ['flow_actions.flow_action.update', $flowAction->id],
                'class' => 'form-horizontal',
                'name' => 'edit_flow_action_form',
                'id' => 'edit_flow_action_form',
                
            ]) !!}

            @include ('flow_actions.form', ['flowAction' => $flowAction,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection