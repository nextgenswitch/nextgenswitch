@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($title) ? $title : __('Call Queue Extension') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('call_queue_extensions.call_queue_extension.index', $call_queue->id) }}" class="btn btn-primary" title="{{ __('Show All Call Queue Extension') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('call_queue_extensions.call_queue_extension.create', $call_queue->id) }}" class="btn btn-primary" title="{{ __('Create New Call Queue Extension') }}">
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

            {!! Form::model($callQueueExtension, [
                'method' => 'PUT',
                'route' => ['call_queue_extensions.call_queue_extension.update', $callQueueExtension->id],
                'class' => 'form-horizontal',
                'name' => 'edit_call_queue_extension_form',
                'id' => 'edit_call_queue_extension_form',
                
            ]) !!}

            @include ('call_queue_extensions.form', ['callQueueExtension' => $callQueueExtension,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection