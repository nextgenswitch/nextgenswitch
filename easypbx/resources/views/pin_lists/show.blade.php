@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($pinList->name) ? $pinList->name : __('Pin List') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['pin_lists.pin_list.destroy', $pinList->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('pin_lists.pin_list.index') }}" class="btn btn-primary" title="{{ __('Show All Pin List') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('pin_lists.pin_list.create') }}" class="btn btn-success" title="{{ __('Create New Pin List') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('pin_lists.pin_list.edit', $pinList->id ) }}" class="btn btn-primary" title="{{ __('Edit Pin List') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Pin List',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Pin List.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($pinList->organization)->name }}</dd>
            <dt>Name</dt>
            <dd>{{ $pinList->name }}</dd>
            <dt>Pin List</dt>
            <dd>{{ $pinList->pin_list }}</dd>
            <dt>Created At</dt>
            <dd>{{ $pinList->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $pinList->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
