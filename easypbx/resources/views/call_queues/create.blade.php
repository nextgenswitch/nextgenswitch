@extends('layouts.app')

@section('title', 'Create New Call Queue')


@section('content')

        <div class="panel-heading clearfix">
                    
            <div class="pull-left">
                <h4 class="mb-4">{{ __('Create New Call Queue') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('call_queues.call_queue.index') }}" class="btn btn-primary" title="{{ __('Show All Call Queue') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

            </div>

        </div>


    {!! Form::open([
        'route' => 'call_queues.call_queue.store',
        'class' => 'form-horizontal ajaxForm dynamicForm',
        'name' => 'create_call_queue_form',
        'id' => 'call_queue_form',
        'path' => route('ring_groups.ring_group.destinations', 0),
    ]) !!}
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#tab_queue" type="button"
                        role="tab" aria-controls="home" aria-selected="true">{{ __('Call Queue') }}</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#tab_extensions" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">{{ __('Extensions') }}</button>
                </li>

            </ul>

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif



        </div>
        <div class="card-body">

            <div class="tab-content" id="myTabContent pb-2">
                <input type="hidden" id="queue" value="{{ optional($callQueue)->id }}">
                <div class="tab-pane fade show active" id="tab_queue" role="tabpanel" aria-labelledby="home-tab">


                    @include ('call_queues.queue_form', ['callQueue' => null])
                </div>



                <div class="tab-pane fade" id="tab_extensions" role="tabpanel" aria-labelledby="profile-tab">
                    @include ('call_queues.extension_form', ['callQueueExtension' => []])
                </div>
            </div>
        </div>


        <div class="card-footer ">
            {!! Form::submit(__('Save Changes'), ['class' => 'btn btn-primary float-right']) !!}
        </div>

    </div>





    {!! Form::close() !!}


@endsection
