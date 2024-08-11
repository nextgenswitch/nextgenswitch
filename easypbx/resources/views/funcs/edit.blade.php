@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($func->name) ? $func->name : __('Func') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('funcs.func.index') }}" class="btn btn-primary" title="{{ __('Show All Func') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('funcs.func.create') }}" class="btn btn-primary" title="{{ __('Create New Func') }}">
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

            {!! Form::model($func, [
                'method' => 'PUT',
                'route' => ['funcs.func.update', $func->id],
                'class' => 'form-horizontal',
                'name' => 'edit_func_form',
                'id' => 'edit_func_form',
                
            ]) !!}

            @include ('funcs.form', ['func' => $func,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection