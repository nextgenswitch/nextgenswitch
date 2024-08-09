@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <div class="pull-left">
                <h4 class="mb-5">{{ __('Create New Ivr') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('ivrs.ivr.index') }}" class="btn btn-primary" title="{{ __('Show All Ivr') }}">
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
                'route' => 'ivrs.ivr.store',
                'class' => 'form-horizontal',
                'name' => 'create_ivr_form',
                'id' => 'create_ivr_form',
                
                ])
            !!}

            @include ('ivrs.form', ['ivr' => null,])
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Add'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection


