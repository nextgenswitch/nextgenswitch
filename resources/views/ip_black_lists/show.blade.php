@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Ip Black List') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['ip_black_lists.ip_black_list.destroy', $ipBlackList->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('ip_black_lists.ip_black_list.index') }}" class="btn btn-primary" title="{{ __('Show All Ip Black List') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ip_black_lists.ip_black_list.create') }}" class="btn btn-success" title="{{ __('Create New Ip Black List') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ip_black_lists.ip_black_list.edit', $ipBlackList->id ) }}" class="btn btn-primary" title="{{ __('Edit Ip Black List') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Ip Black List',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Ip Black List.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($ipBlackList->organization)->name }}</dd>
            <dt>Ip</dt>
            <dd>{{ $ipBlackList->ip }}</dd>
            <dt>Subnet</dt>
            <dd>{{ $ipBlackList->subnet }}</dd>
            <dt>Created At</dt>
            <dd>{{ $ipBlackList->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $ipBlackList->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
