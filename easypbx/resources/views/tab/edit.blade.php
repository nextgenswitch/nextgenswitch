@extends('layouts.app')

@section('title', 'Tab')


@section('content')
    <h5 class="card-title">Edit IVR #{{ $ivr->name }}</h5>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#ivr" type="button" role="tab" aria-controls="home" aria-selected="true">IVR</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#ivr_action" type="button" role="tab" aria-controls="profile" aria-selected="false">IVR Action</button>
        </li>
      </ul>
      

        {!! Form::open([
            'route' => ['ivrs.ivr.update', $ivr->id],
            'class' => ['form-horizontal', 'validation'],
            'name' => 'create_ivr_action_form',
            'id' => 'ivr_action_form'
            ])
        !!}

        @method('put')

        <div class="tab-content" id="myTabContent">
            <input type="hidden" id="ivr_id" value="{{ optional($ivr)->id }}">
            <div class="tab-pane fade show active" id="ivr" role="tabpanel" aria-labelledby="home-tab">
                @include ('tab.ivr_form', ['ivr' => $ivr])

            </div>

            <div class="tab-pane fade" id="ivr_action" role="tabpanel" aria-labelledby="profile-tab">
                @include ('tab.ivr_action_form', ['ivrAction' => $ivr->actions])

            </div>
        </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Add'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

        {!! Form::close() !!}

      


@endsection
