@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="pull-left">
                <h4 class="mb-5">{{ __('Create New Stream') }}</h4>
            </div>
            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('streams.stream.index') }}" class="btn btn-primary" title="{{ __('Show All Streams') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
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
                'route' => 'streams.stream.store',
                'class' => 'form-horizontal',
                'name' => 'create_stream_form',
                'id' => 'create_stream_form',
            ]) !!}
                @include('streams.form', ['stream' => null])
            {!! Form::close() !!}
        </div>
    </div>
@endsection
