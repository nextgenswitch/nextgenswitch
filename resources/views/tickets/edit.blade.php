@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($ticket->name) ? $ticket->name : __('Ticket') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('tickets.ticket.index') }}" class="btn btn-primary" title="{{ __('Show All Ticket') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('tickets.ticket.create') }}" class="btn btn-primary" title="{{ __('Create New Ticket') }}">
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

            {!! Form::model($ticket, [
                'method' => 'PUT',
                'route' => ['tickets.ticket.update', $ticket->id],
                'class' => 'form-horizontal',
                'name' => 'edit_ticket_form',
                'id' => 'edit_ticket_form',
                
            ]) !!}

            @include ('tickets.form', ['ticket' => $ticket,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection