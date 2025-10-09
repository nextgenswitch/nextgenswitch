@extends('layouts.app')

@section('title', 'Dialer Test')

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12 py-3">

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sip_login_modal">
                Login
            </button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dialer_modal">
                Dialpad
            </button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#calling_modal">
                Ringing
            </button>
        </div>

    </div>

    @include('test.dialpad')
@endsection
