@extends('layouts.app')

@section('title', __('contact.edit_contact'))

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">{{ !empty($contact->name) ? $contact->name : __('contact.contact') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('contacts.contact.index') }}" class="btn btn-primary" title="{{ __('contact.show_all_contact') }}">
                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('contacts.contact.create') }}" class="btn btn-primary" title="{{ __('contact.create_title') }}">
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

            {!! Form::model($contact, [
                'method' => 'PUT',
                'route' => ['contacts.contact.update', $contact->id],
                'class' => 'form-horizontal',
                'name' => 'edit_contact_form',
                'id' => 'edit_contact_form',
                
            ]) !!}

            @include ('contacts.form', ['contact' => $contact,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection