@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($user->name) ? $user->name : __('User') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('users.user.index') }}" class="btn btn-primary" title="{{ __('Show All User') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('users.user.create') }}" class="btn btn-primary" title="{{ __('Create New User') }}">
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

            {!! Form::model($user, [
                'method' => 'PUT',
                'route' => ['users.user.update', $user->id],
                'class' => 'form-horizontal',
                'name' => 'edit_user_form',
                'id' => 'edit_user_form',
                
            ]) !!}

            @include ('users.form', ['user' => $user,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection