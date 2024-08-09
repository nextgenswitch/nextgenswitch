@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($voiceFile->name) ? $voiceFile->name : __('Voice File') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['voice_files.voice_file.destroy', $voiceFile->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('voice_files.voice_file.index') }}" class="btn btn-primary" title="{{ __('Show All Voice File') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('voice_files.voice_file.create') }}" class="btn btn-success" title="{{ __('Create New Voice File') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('voice_files.voice_file.edit', $voiceFile->id ) }}" class="btn btn-primary" title="{{ __('Edit Voice File') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Voice File',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Voice File.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ optional($voiceFile->user)->name }}</dd>
            <dt>Name</dt>
            <dd>{{ $voiceFile->name }}</dd>
            <dt>File Name</dt>
            <dd>{{ $voiceFile->file_name }}</dd>
            <dt>Created At</dt>
            <dd>{{ $voiceFile->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $voiceFile->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
