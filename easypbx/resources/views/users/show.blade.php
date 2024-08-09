@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($user->name) ? $user->name : __('User') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['users.user.destroy', $user->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('users.user.index') }}" class="btn btn-primary" title="{{ __('Show All User') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('users.user.create') }}" class="btn btn-success" title="{{ __('Create New User') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('users.user.edit', $user->id ) }}" class="btn btn-primary" title="{{ __('Edit User') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete User',
                            'onclick' => "return confirm(" .  __('Click Ok to delete User.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $user->created_at }}</dd>
            <dt>Email</dt>
            <dd>{{ $user->email }}</dd>
            <dt>Email Verified At</dt>
            <dd>{{ $user->email_verified_at }}</dd>
            <dt>Name</dt>
            <dd>{{ $user->name }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($user->organization)->name }}</dd>
            <dt>Password</dt>
            <dd>{{ $user->password }}</dd>
            <dt>Remember Token</dt>
            <dd>{{ $user->remember_token }}</dd>
            <dt>Role</dt>
            <dd>{{ $user->role }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $user->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
