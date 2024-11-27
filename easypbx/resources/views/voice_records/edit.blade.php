@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($voiceRecord->name) ? $voiceRecord->name : __('Voice Record') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('voice_records.voice_record.index') }}" class="btn btn-primary" title="{{ __('Show All Voice Record') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('voice_records.voice_record.create') }}" class="btn btn-primary" title="{{ __('Create New Voice Record') }}">
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

            {!! Form::model($voiceRecord, [
                'method' => 'PUT',
                'route' => ['voice_records.voice_record.update', $voiceRecord->id],
                'class' => 'form-horizontal',
                'name' => 'edit_voice_record_form',
                'id' => 'edit_voice_record_form',
                
            ]) !!}

            @include ('voice_records.form', ['voiceRecord' => $voiceRecord,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection