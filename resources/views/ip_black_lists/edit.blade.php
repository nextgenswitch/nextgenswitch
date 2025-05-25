@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($title) ? $title : __('Ip Black List') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('ip_black_lists.ip_black_list.index') }}" class="btn btn-primary" title="{{ __('Show All Ip Black List') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('ip_black_lists.ip_black_list.create') }}" class="btn btn-primary" title="{{ __('Create New Ip Black List') }}">
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

            {!! Form::model($ipBlackList, [
                'method' => 'PUT',
                'route' => ['ip_black_lists.ip_black_list.update', $ipBlackList->id],
                'class' => 'form-horizontal',
                'name' => 'edit_ip_black_list_form',
                'id' => 'edit_ip_black_list_form',
                
            ]) !!}

            @include ('ip_black_lists.form', ['ipBlackList' => $ipBlackList,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection