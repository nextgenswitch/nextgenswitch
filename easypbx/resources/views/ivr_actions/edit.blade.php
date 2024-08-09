@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($title) ? $title : __('Ivr Action') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('ivr_actions.ivr_action.index', $ivrAction->ivr_id) }}" class="btn btn-primary" title="{{ __('Show All Ivr Action') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('ivr_actions.ivr_action.create', $ivrAction->ivr_id) }}" class="btn btn-primary" title="{{ __('Create New Ivr Action') }}">
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

            {!! Form::model($ivrAction, [
                'method' => 'PUT',
                'route' => ['ivr_actions.ivr_action.update', $ivrAction->id],
                'class' => 'form-horizontal',
                'name' => 'edit_ivr_action_form',
                'id' => 'edit_ivr_action_form',
                
            ]) !!}

            @include ('ivr_actions.form', ['ivrAction' => $ivrAction,])

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

<script type="text/javascript">
    $(document).ready(function(){
        destinations = "{{ route('ivr_actions.ivr_action.destinations', 0) }}"

        $('#FormModal').on('change', '#edit_ivr_action_form', function(e){
            e.preventDefault()

            var val = $(this).val().trim()

            if(val != undefined && val != ''){
                route = destinations.trim().slice(0, -1) + val
                console.log(route)

                $.get(route, function(res){
                    console.log(res)
                    $("#destination_id").html(res)
                })

            }
            else
                $("#destination_id").html('<option> Select destination </option>')

        })


        digits_path = "{{ route('ivr_actions.ivr_action.digits', 0) }}"

        $('#FormModal, #edit_ivr_action_form').on('change', '#ivr_id', function(e){
            e.preventDefault()

            var val = $(this).val().trim()

            if(val != undefined && val != ''){
                route = digits_path.trim().slice(0, -1) + val
                console.log(route)

                $.get(route, function(res){
                    console.log(res)
                    $("#digit").html(res)
                })

            }
            else
                $("#digit").html('<option> Select ivr digit </option>')

        })



    })
</script>


@endpush
