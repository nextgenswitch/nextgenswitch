@extends('layouts.app')
@push('css')    
    <link rel="stylesheet" href="{{ asset('js/jquery-ui/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap4.min.css') }}">
@endpush

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-3">Edit Lead #{{ $lead->id }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('leads.lead.index') }}" class="btn btn-primary" title="{{ __('Show All Lead') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('leads.lead.create') }}" class="btn btn-primary" title="{{ __('Create New Lead') }}">
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

            {!! Form::model($lead, [
                'method' => 'PUT',
                'route' => ['leads.lead.update', $lead->id],
                'class' => 'form-horizontal',
                'name' => 'edit_lead_form',
                'id' => 'edit_lead_form',
                
            ]) !!}

            @include ('leads.form', ['lead' => $lead,])

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
<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/selectize.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $("#source").selectize({
            delimiter: ",",
            persist: false,
            create: function (input) {
                return {
                    value: input,
                    text: input,
                };
            },
        });

        $("#status").selectize({
            delimiter: ",",
            persist: false,
            create: function (input) {
                return {
                    value: input,
                    text: input,
                };
            },
        });
        
    })
</script>
@endpush