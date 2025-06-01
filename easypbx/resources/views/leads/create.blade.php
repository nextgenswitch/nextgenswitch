@extends('layouts.app')

@push('css')    
    <link rel="stylesheet" href="{{ asset('js/jquery-ui/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap4.min.css') }}">
@endpush

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <div class="pull-left">
                <h4 class="mb-3">{{ __('Create New Lead') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('leads.lead.index') }}" class="btn btn-primary" title="{{ __('Show All Lead') }}">
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

            {!! Form::open([
                'route' => 'leads.lead.store',
                'class' => 'form-horizontal',
                'name' => 'create_lead_form',
                'id' => 'create_lead_form',
                
                ])
            !!}

            @include ('leads.form', ['lead' => null,])
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Add'), ['class' => 'btn btn-primary']) !!}
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