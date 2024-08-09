@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Sms') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['sms.sms.destroy', $sms->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('sms.sms.index') }}" class="btn btn-primary" title="{{ __('Show All Sms') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('sms.sms.create') }}" class="btn btn-success" title="{{ __('Create New Sms') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('sms.sms.edit', $sms->id ) }}" class="btn btn-primary" title="{{ __('Edit Sms') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Sms',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Sms.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($sms->organization)->name }}</dd>
            <dt>Content</dt>
            <dd>{{ $sms->content }}</dd>
            <dt>Sms Count</dt>
            <dd>{{ $sms->sms_count }}</dd>
            <dt>Status</dt>
            <dd>{{ $sms->status }}</dd>
            <dt>Created At</dt>
            <dd>{{ $sms->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $sms->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
