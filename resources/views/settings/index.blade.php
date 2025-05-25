@extends('layouts.app')

@section('content')
@if(Session::has('success_message'))
<div class="alert alert-success">
    <span class="glyphicon glyphicon-ok"></span>
    {!! session('success_message') !!}

    <button type="button" class="close" data-dismiss="alert" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>
@endif

    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <div class="pull-left">
                <h4>{{ __(ucfirst($group)) }} {{ __('Settings') }}</h4>
                <p class="mb-5">{{ __('Customizing your settings') }}</p>
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

            {!! Form::open([
                'route' => 'settings.setting.store',
                'class' => 'form-horizontal',
                'name' => 'create_setting_form',
                'id' => 'create_setting_form',
                
                ])
            !!}
            <input type="hidden" name="group" value="{{ $group }}"/>  
            


            @include ('settings.' . $group , ['settings' => $settings,])

        


            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Save Changes'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection