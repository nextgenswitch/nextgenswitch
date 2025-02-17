@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($outboundRoute->name) ? $outboundRoute->name : __('Outbound Route') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('outbound_routes.outbound_route.index') }}" class="btn btn-primary" title="{{ __('Show All Outbound Route') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
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

            {!! Form::model($outboundRoute, [
                'method' => 'PUT',
                'route' => ['outbound_routes.outbound_route.update', $outboundRoute->id],
                'class' => 'form-horizontal ajaxForm',
                'name' => 'edit_outbound_route_form',
                'id' => 'edit_outbound_route_form',
                'path' => route('outbound_routes.outbound_route.destinations', $outboundRoute->id)
            ]) !!}

            @include ($api == 0 ?  'outbound_routes.form' : 'outbound_routes.api_form', ['outboundRoute' => $outboundRoute,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection