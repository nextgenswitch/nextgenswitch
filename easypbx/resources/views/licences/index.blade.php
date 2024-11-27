@extends('layouts.app')
    @php
        $title = env('APP_NAME', 'EasyPBX');
       // if(config('licence.brand_name')) $title = config('licence.brand_name')
    @endphp

@section('title', 'Licence - ' . $title)

@push('css')
    <style>
        .tile {
          background: transparent;
          border-radius: 3px;
          padding: 0px;
          box-shadow: none;
          margin-bottom: 30px;
          -webkit-transition: all 0.3s ease-in-out;
          -o-transition: all 0.3s ease-in-out;
          transition: all 0.3s ease-in-out;
      }
    </style>
@endpush

@section('content')
    @include('partials.message')
    <div class="card">

        <div class="card-header tile-title">{{ __("Active {$title}") }}</div>

        <div class="card-body">
          @include('licences.create_account_form')
        </div>
    </div>
  



@endsection
