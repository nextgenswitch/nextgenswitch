@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($hotdesk->name) ? $hotdesk->name : __('Hotdesk') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('hotdesks.hotdesk.index') }}" class="btn btn-primary" title="{{ __('Show All Hotdesk') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('hotdesks.hotdesk.create') }}" class="btn btn-primary" title="{{ __('Create New Hotdesk') }}">
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

            {!! Form::model($hotdesk, [
                'method' => 'PUT',
                'route' => ['hotdesks.hotdesk.update', $hotdesk->id],
                'class' => 'form-horizontal',
                'name' => 'edit_hotdesk_form',
                'id' => 'edit_hotdesk_form',
                
            ]) !!}

            @include ('hotdesks.form', ['hotdesk' => $hotdesk,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection