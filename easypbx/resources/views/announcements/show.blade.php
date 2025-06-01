@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($announcement->name) ? $announcement->name : __('Announcement') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['announcements.announcement.destroy', $announcement->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('announcements.announcement.index') }}" class="btn btn-primary" title="{{ __('Show All Announcement') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('announcements.announcement.create') }}" class="btn btn-success" title="{{ __('Create New Announcement') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('announcements.announcement.edit', $announcement->id ) }}" class="btn btn-primary" title="{{ __('Edit Announcement') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Announcement',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Announcement.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($announcement->organization)->name }}</dd>
            <dt>Name</dt>
            <dd>{{ $announcement->name }}</dd>
            <dt>Voice</dt>
            <dd>{{ optional($announcement->voice)->id }}</dd>
            <dt>Function</dt>
            <dd>{{ optional($announcement->function)->id }}</dd>
            <dt>Destination</dt>
            <dd>{{ optional($announcement->destination)->id }}</dd>
            <dt>Created At</dt>
            <dd>{{ $announcement->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $announcement->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
