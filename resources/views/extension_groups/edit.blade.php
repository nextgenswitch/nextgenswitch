@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($extensionGroup->name) ? $extensionGroup->name : __('Extension Group') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('extension_groups.extension_group.index') }}" class="btn btn-primary" title="{{ __('Show All Extension Group') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('extension_groups.extension_group.create') }}" class="btn btn-primary" title="{{ __('Create New Extension Group') }}">
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

            {!! Form::model($extensionGroup, [
                'method' => 'PUT',
                'route' => ['extension_groups.extension_group.update', $extensionGroup->id],
                'class' => 'form-horizontal',
                'name' => 'edit_extension_group_form',
                'id' => 'edit_extension_group_form',
                
            ]) !!}

            @include ('extension_groups.form', ['extensionGroup' => $extensionGroup,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection