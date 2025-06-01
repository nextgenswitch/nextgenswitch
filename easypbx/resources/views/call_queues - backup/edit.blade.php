@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-5">{{ !empty($title) ? $title : __('Call Queue') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('call_queues.call_queue.index') }}" class="btn btn-primary" title="{{ __('Show All Call Queue') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('call_queues.call_queue.create') }}" class="btn btn-primary" title="{{ __('Create New Call Queue') }}">
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

            {!! Form::model($callQueue, [
                'method' => 'PUT',
                'route' => ['call_queues.call_queue.update', $callQueue->id],
                'class' => 'form-horizontal',
                'name' => 'edit_call_queue_form',
                'id' => 'edit_call_queue_form',
                
            ]) !!}

            @include ('call_queues.form', ['callQueue' => $callQueue,])

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
<script src="{{ asset('js/index.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function(){
      path = "{{ route('ring_groups.ring_group.destinations', 0) }}"

      $(document).on('change', '#function_id', function(e){
          e.preventDefault()

          var val = $(this).val().trim()

          if(val != undefined && val != ''){
              route = path.trim().slice(0, -1) + val
              console.log(route)

              $.get(route, function(res){
                  console.log(res)
                  $("#destination_id").html(res)
              })

          }
          else
              $("#destination_id").html('<option> Select destination </option>')

      })


  })
</script>

@endpush