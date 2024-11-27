@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($voiceRecord->name) ? $voiceRecord->name : __('Voice Record') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['voice_records.voice_record.destroy', $voiceRecord->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('voice_records.voice_record.index') }}" class="btn btn-primary" title="{{ __('Show All Voice Record') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('voice_records.voice_record.create') }}" class="btn btn-success" title="{{ __('Create New Voice Record') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('voice_records.voice_record.edit', $voiceRecord->id ) }}" class="btn btn-primary" title="{{ __('Edit Voice Record') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Voice Record',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Voice Record.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $voiceRecord->name }}</dd>
            <dt>Voice</dt>
            <dd>{{ optional($voiceRecord->voice)->id }}</dd>
            <dt>Is Transcript</dt>
            <dd>{{ ($voiceRecord->is_transcript) ? 'Yes' : 'No' }}</dd>
            <dt>Text</dt>
            <dd>{{ $voiceRecord->text }}</dd>
            <dt>Play Beep</dt>
            <dd>{{ ($voiceRecord->play_beep) ? 'Yes' : 'No' }}</dd>
            <dt>Is Send Email</dt>
            <dd>{{ ($voiceRecord->is_send_email) ? 'Yes' : 'No' }}</dd>
            <dt>Email</dt>
            <dd>{{ $voiceRecord->email }}</dd>
            <dt>Created At</dt>
            <dd>{{ $voiceRecord->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $voiceRecord->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
