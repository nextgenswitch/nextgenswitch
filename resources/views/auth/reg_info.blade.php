@extends('auth.layout')

@section('title', 'Registration Information')

@section('content')

<section class="login-content">
    
      <div class="login-box">
        <div class="card">
            <div class="card-header">
                <h4> Registration Information </h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered"> 
                    <tr>
                        <th>PBX Portal</th>
                        <td><a href="http://{{ $user->organization->domain }}">{{"http://". $user->organization->domain }}</a></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td>{{ $user->password }}</td>
                    </tr>
                    <tr>
                        <th>Sip Server</th>
                        <td>{{ $user->organization->domain }}</td>
                    </tr>
                    <tr>
                        <th>Sip Port</th>
                        <td>5060(TCP/UPD) 5061(TLS)</td>
                    </tr>
                </table>

                <div class="text-center">
                    <a href="{{ $user->url }}">
                        <button class="btn btn-primary"><i class="fa fa-sign-in fa-lg fa-fw"></i>{{ __('GO TO LOGIN') }}</button>
                    </a>
                </div>
            </div>
        </div>
      </div>
    </section>


@endsection
