@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($aiBot->name) ? $aiBot->name : __('AI Assistant') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('ai_bots.ai_bot.index') }}" class="btn btn-primary" title="{{ __('Show All AI Assistant') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('ai_bots.ai_bot.create') }}" class="btn btn-primary" title="{{ __('Create New AI Assistant') }}">
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

            {!! Form::model($aiBot, [
                'method' => 'PUT',
                'route' => ['ai_bots.ai_bot.update', $aiBot->id],
                'class' => 'form-horizontal',
                'name' => 'edit_ai_bot_form',
                'id' => 'edit_ai_bot_form',
                
            ]) !!}

            @include ('ai_bots.form', ['aiBot' => $aiBot,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection