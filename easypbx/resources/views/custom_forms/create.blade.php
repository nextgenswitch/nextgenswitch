@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <div class="pull-left">
                <h4 class="mb-3">{{ __('Create New Form') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#custom_form_modal" title="{{ __('Add New Field') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span> {{ __('Add New Field') }}
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

            {!! Form::open([
                'route' => 'custom_forms.custom_form.store',
                'class' => 'form-horizontal custom-form',
                'name' => 'create_custom_form_form',
                'id' => 'create_custom_form_form',
                
                ])
            !!}

            @include ('custom_forms.form', ['customForm' => null,])
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Add Form'), ['class' => 'btn btn-primary submit-custom-form']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection


