@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($api->title) ? $api->title : __('Api') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('apis.api.index') }}" class="btn btn-primary" title="{{ __('Show All Api') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('apis.api.create') }}" class="btn btn-primary" title="{{ __('Create New Api') }}">
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

            {!! Form::model($api, [
                'method' => 'PUT',
                'route' => ['apis.api.update', $api->id],
                'class' => 'form-horizontal',
                'name' => 'edit_api_form',
                'id' => 'edit_api_form',
                
            ]) !!}

            @include ('apis.form', ['api' => $api,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection