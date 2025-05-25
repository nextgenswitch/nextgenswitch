@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($application->name) ? $application->name : __('Application') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('applications.application.index') }}" class="btn btn-primary" title="{{ __('Show All Application') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('applications.application.create') }}" class="btn btn-primary" title="{{ __('Create New Application') }}">
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

            {!! Form::model($application, [
                'method' => 'PUT',
                'route' => ['applications.application.update', $application->id],
                'class' => 'form-horizontal',
                'name' => 'edit_application_form',
                'id' => 'edit_application_form',
                
            ]) !!}

            @include ('applications.form', ['application' => $application,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection