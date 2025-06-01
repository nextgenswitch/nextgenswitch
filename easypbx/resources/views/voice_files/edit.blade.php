@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($voiceFile->name) ? $voiceFile->name : __('Voice File') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('voice_files.voice_file.index') }}" class="btn btn-primary" title="{{ __('Show All Voice File') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('voice_files.voice_file.create') }}" class="btn btn-primary" title="{{ __('Create New Voice File') }}">
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

            {!! Form::model($voiceFile, [
                'method' => 'PUT',
                'route' => ['voice_files.voice_file.update', $voiceFile->id],
                'class' => 'form-horizontal',
                'name' => 'edit_voice_file_form',
                'id' => 'edit_voice_file_form',
                'enctype' => 'multipart/form-data'
            ]) !!}

            @include ('voice_files.form', ['voiceFile' => $voiceFile,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection