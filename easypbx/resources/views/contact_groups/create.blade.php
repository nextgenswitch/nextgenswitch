@extends('layouts.app')

@section('title', __('Create Contact Group'))

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <div class="pull-left">
                <h4 class="mt-5 mb-5">{{ __('Create Contact Group')}}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('contact_groups.contact_group.index') }}" class="btn btn-primary" title="{{ __('Show all contact group')}}">
                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
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
                'route' => 'contact_groups.contact_group.store',
                'class' => 'form-horizontal',
                'name' => 'create_contact_group_form',
                'id' => 'create_contact_group_form',
                
                ])
            !!}

            @include ('contact_groups.form', ['contactGroup' => null,])
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Add'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection


