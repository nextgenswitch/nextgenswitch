@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ __('Edit Function') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('custom_funcs.custom_func.index') }}" class="btn btn-primary" title="{{ __('Show All Custom Func') }}">
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

            {!! Form::model($customFunc, [
                'method' => 'PUT',
                'route' => ['custom_funcs.custom_func.update', $customFunc->id],
                'class' => 'form-horizontal custom_func_form',
                'name' => 'edit_custom_func_form',
                'id' => 'edit_custom_func_form',
                
            ]) !!}

            @include ('custom_funcs.form', ['customFunc' => $customFunc,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection