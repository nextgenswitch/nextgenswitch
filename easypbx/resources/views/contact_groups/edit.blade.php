@extends('layouts.app')

@section('title', __('Edit contact group'))

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">{{ !empty($contactGroup->name) ? $contactGroup->name : __('Contact Groups') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('contact_groups.contact_group.index') }}" class="btn btn-primary" title="{{ __('Show all contact group')}}">
                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('contact_groups.contact_group.create') }}" class="btn btn-primary" title="{{ __('Create Contact Group')}}">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
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

            {!! Form::model($contactGroup, [
                'method' => 'PUT',
                'route' => ['contact_groups.contact_group.update', $contactGroup->id],
                'class' => 'form-horizontal',
                'name' => 'edit_contact_group_form',
                'id' => 'edit_contact_group_form',
                
            ]) !!}

            @include ('contact_groups.form', ['contactGroup' => $contactGroup,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection