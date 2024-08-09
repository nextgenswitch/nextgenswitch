@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($customForm->name) ? $customForm->name : __('Custom Form') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['custom_forms.custom_form.destroy', $customForm->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('custom_forms.custom_form.index') }}" class="btn btn-primary" title="{{ __('Show All Custom Form') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('custom_forms.custom_form.create') }}" class="btn btn-success" title="{{ __('Create New Custom Form') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('custom_forms.custom_form.edit', $customForm->id ) }}" class="btn btn-primary" title="{{ __('Edit Custom Form') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Custom Form',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Custom Form.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $customForm->created_at }}</dd>
            <dt>Fields</dt>
            <dd>{{ $customForm->fields }}</dd>
            <dt>Name</dt>
            <dd>{{ $customForm->name }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($customForm->organization)->name }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $customForm->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
