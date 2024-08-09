@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($ivr->name) ? $ivr->name : __('Ivr') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('ivrs.ivr.index') }}" class="btn btn-primary" title="{{ __('Show All Ivr') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('ivrs.ivr.create') }}" class="btn btn-primary" title="{{ __('Create New Ivr') }}">
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

            {!! Form::model($ivr, [
                'method' => 'PUT',
                'route' => ['ivrs.ivr.update', $ivr->id],
                'class' => 'form-horizontal',
                'name' => 'edit_ivr_form',
                'id' => 'edit_ivr_form',
                
            ]) !!}

            @include ('ivrs.form', ['ivr' => $ivr,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection