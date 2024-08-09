@extends('layouts.app')

@section('title', __('Create New Broadcast'))



@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <div class="pull-left">
                <h4 class="mb-5">{{ __('Create new Broadcast') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('broadcasts.broadcast.index') }}" class="btn btn-primary" title="{{ __('show all Broadcast') }}">
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
                'route' => 'broadcasts.broadcast.store',
                'name' => 'create_campaign_form',
                'id' => 'campaign_form',
                'class' => 'form-horizontal ajaxForm',
                'path' => route('broadcasts.broadcast.destinations', 0)
                
                ])
            !!}

            @include ('broadcasts.form', ['campaign' => $campaign,])
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    {!! Form::submit(__('Add Broadcast'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@push('script')
    
    <script> 
        $(document).ready(function(e){
            


        })
    </script>

@endpush


