@extends('layouts.app')

@section('title', 'Create New IVR')


@section('content')
    
    <div class="panel-heading clearfix">
                    
        <div class="pull-left">
            <h4 class="mb-4">{{ __('Create New IVR') }}</h4>
        </div>

        <div class="btn-group btn-group-sm pull-right" role="group">

            <a href="{{ route('ivrs.ivr.index') }}" class="btn btn-primary" title="{{ __('Show All IVR') }}">
                <span class="fa fa-list" aria-hidden="true"></span>
            </a>

        </div>

    </div>


    {!! Form::open([
        'route' => 'ivrs.ivr.store',
        'class' => 'form-horizontal ajaxForm',
        'name' => 'create_ivr_action_form ajaxForm',
        'id' => 'ivr_action_form',
        'path' => route('ivrs.ivr.destinations', 0),
    ]) !!}


    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#ivr" type="button"
                        role="tab" aria-controls="home" aria-selected="true">{{ __('IVR') }}</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#ivr_action" type="button"
                        role="tab" aria-controls="profile" aria-selected="false"> {{ __("IVR Entries") }}</button>
                </li>

            </ul>
        </div>




        <div class="card-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif


            <div class="tab-content" id="myTabContent">
                <input type="hidden" id="ivr_id" value="{{ optional($ivr)->id }}">
                <div class="tab-pane fade show active" id="ivr" role="tabpanel" aria-labelledby="home-tab">
                    @include ('ivrs.ivr_form', ['ivr' => null])

                </div>

                <div class="tab-pane fade" id="ivr_action" role="tabpanel" aria-labelledby="profile-tab">
                    @include ('ivrs.ivr_action_form', ['ivrAction' => []])

                </div>
            </div>
        </div>

        <div class="card-footer ">
            {!! Form::submit(__('Save Changes'), ['class' => 'btn btn-primary float-right']) !!}
        </div>

    </div>

    {!! Form::close() !!}




@endsection
