@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($survey->name) ? $survey->name : __('Survey') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('surveys.survey.index') }}" class="btn btn-primary" title="{{ __('Show All Survey') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('surveys.survey.create') }}" class="btn btn-primary" title="{{ __('Create New Survey') }}">
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

            {!! Form::model($survey, [
                'method' => 'PUT',
                'route' => ['surveys.survey.update', $survey->id],
                'class' => 'form-horizontal',
                'name' => 'edit_survey_form',
                'id' => 'edit_survey_form',
                'keys' => optional($survey)->keys
            ]) !!}

            @include ('surveys.form', ['survey' => $survey,])

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@push('script')
<script src="{{ asset('js/play.js') }}"></script>
@endpush