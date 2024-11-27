@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix mb-3">

            <div class="pull-left">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="extension-tab" data-toggle="tab" href="#edit_extension_form">{{ __('Edit Hotdesk') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="allow-ip-tab" data-allow-ip-route="{{ route('extensions.extension.allow.ip', $hotdesk->sip_user_id) }}" data-allow-ip="{{ $hotdesk->sipuser->allow_ip }}" data-toggle="tab" href="#allow_ip_list" role="tab">{{ __('Allow IP List')}}</a>
                        </li>
                </ul>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('hotdesks.hotdesk.index') }}" class="btn btn-primary" title="{{ __('Show All Hotdesk') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('hotdesks.hotdesk.create') }}" class="btn btn-primary" title="{{ __('Create New Hotdesk') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>

            </div>
        </div>

        <div class="panel-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="edit_extension_form">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {!! Form::model($hotdesk, [
                'method' => 'PUT',
                'route' => ['hotdesks.hotdesk.update', $hotdesk->id],
                'class' => 'form-horizontal',
                'name' => 'edit_hotdesk_form',
                'id' => 'edit_hotdesk_form',
                
            ]) !!}

            @include ('hotdesks.form', ['hotdesk' => $hotdesk,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

            </div>

            <div class="tab-pane fade" id="allow_ip_list">
                    
                    <div class="row">
                        <div class="col-lg-4 offset-lg-3">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter allow ip address" id="add_allow_ip">
                                <button class="btn btn-primary" type="button" id="btn_add_ip">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>

                            <div id="ip_contents">
                                
                            </div>
                        </div>

                        
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
