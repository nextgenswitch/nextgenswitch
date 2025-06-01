@extends('layouts.app')

@section('title', 'Tab')


@section('content')
    <h5 class="card-title">Create New IVR</h5>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#ivr" type="button" role="tab" aria-controls="home" aria-selected="true">IVR</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#ivr_action" type="button" role="tab" aria-controls="profile" aria-selected="false">IVR Action</button>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">

        {!! Form::open([
            'route' => 'ivr_actions.ivr_action.store',
            'class' => 'form-horizontal',
            'name' => 'create_ivr_action_form',
            'id' => 'create_ivr_action_form',
            
            ])
        !!}
        
            <div class="tab-pane fade show active" id="ivr" role="tabpanel" aria-labelledby="home-tab">
                @include ('ivrs.form', ['ivr' => null,])

            </div>

            <div class="tab-pane fade" id="ivr_action" role="tabpanel" aria-labelledby="profile-tab">
                @include ('ivr_actions.form', ['ivrAction' => array()])

            </div>
        
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Add'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
        {!! Form::close() !!}

      </div>


@endsection
