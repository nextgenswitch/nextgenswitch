@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('name',old('name', optional($aiBot)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('voice_id') has-error @enderror">
        {!! Form::label('voice_id', __('Welcome Voice'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>
        <div class="input-group voice-preview">
            {!! Form::select('voice_id', $voices, old('voice_id', optional($aiBot)->voice_id), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Select voice'),
            ]) !!}

            <div class="input-group-append">
                <button class="btn btn-outline-secondary play" type="button">
                    <i class="fa fa-play"></i>
                </button>

                <button class="btn btn-outline-secondary stop d-none" type="button">
                    <i class="fa fa-stop"></i>
                </button>

            </div>

        </div>
        @error('voice_id')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('waiting_tone') has-error @enderror">
        {!! Form::label('waiting_tone', __('Waiting Tone'), ['class' => 'control-label']) !!}
        
        <div class="input-group voice-preview">
            {!! Form::select('waiting_tone', $voices, old('waiting_tone', optional($aiBot)->waiting_tone), [
                'class' => 'form-control',
                'required' => false,
                'placeholder' => __('Select voice'),
            ]) !!}

            <div class="input-group-append">
                <button class="btn btn-outline-secondary play" type="button">
                    <i class="fa fa-play"></i>
                </button>

                <button class="btn btn-outline-secondary stop d-none" type="button">
                    <i class="fa fa-stop"></i>
                </button>

            </div>

        </div>
        @error('waiting_tone')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('inaudible_voice') has-error @enderror">
        {!! Form::label('inaudible_voice', __('Inaudible Voice'), ['class' => 'control-label']) !!}
        
        <div class="input-group voice-preview">
            {!! Form::select('inaudible_voice', $voices, old('inaudible_voice', optional($aiBot)->inaudible_voice), [
                'class' => 'form-control',
                'required' => false,
                'placeholder' => __('Select voice'),
            ]) !!}

            <div class="input-group-append">
                <button class="btn btn-outline-secondary play" type="button">
                    <i class="fa fa-play"></i>
                </button>

                <button class="btn btn-outline-secondary stop d-none" type="button">
                    <i class="fa fa-stop"></i>
                </button>

            </div>

        </div>
        @error('inaudible_voice')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('listening_tone') has-error @enderror">
        {!! Form::label('listening_tone', __('Listening Tone'), ['class' => 'control-label']) !!}
        
        <div class="input-group voice-preview">
            {!! Form::select('listening_tone', $voices, old('listening_tone', optional($aiBot)->listening_tone), [
                'class' => 'form-control',
                'required' => false,
                'placeholder' => __('Select voice'),
            ]) !!}

            <div class="input-group-append">
                <button class="btn btn-outline-secondary play" type="button">
                    <i class="fa fa-play"></i>
                </button>

                <button class="btn btn-outline-secondary stop d-none" type="button">
                    <i class="fa fa-stop"></i>
                </button>

            </div>

        </div>
        @error('listening_tone')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('call_transfer_tone') has-error @enderror">
        {!! Form::label('call_transfer_tone', __('Call Transfer Tone'), ['class' => 'control-label']) !!}
        
        <div class="input-group voice-preview">
            {!! Form::select('call_transfer_tone', $voices, old('call_transfer_tone', optional($aiBot)->call_transfer_tone), [
                'class' => 'form-control',
                'required' => false,
                'placeholder' => __('Select voice'),
            ]) !!}

            <div class="input-group-append">
                <button class="btn btn-outline-secondary play" type="button">
                    <i class="fa fa-play"></i>
                </button>

                <button class="btn btn-outline-secondary stop d-none" type="button">
                    <i class="fa fa-stop"></i>
                </button>

            </div>

        </div>
        @error('call_transfer_tone')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
<div class="form-group @error('llm_provider_id') has-error @enderror">
    {!! Form::label('llm_provider_id',__('LLM Provider Profile'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
    {!! Form::select('llm_provider_id',  $llm_profiles, old('provider', optional($aiBot)->llm_provider_id), ['class' => 'form-control', 'placeholder' => __('Select LLM Provider'), 'required' => true]) !!}
    @error('llm_provider_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('tts_profile_id') has-error @enderror">
        {!! Form::label('tts_profile_id', __('TTS Profile'), ['class' => 'control-label']) !!}
        
        <div class="input-group voice-preview">
            {!! Form::select('tts_profile_id', $tts_profiles, old('tts_profile_id', optional($aiBot)->tts_profile_id), [
                'class' => 'form-control',
                'required' => false,
                'placeholder' => __('Select TTS Profile'),
            ]) !!}
        </div>
        @error('tts_profile_id')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('stt_profile_id') has-error @enderror">
        {!! Form::label('stt_profile_id', __('STT Profile'), ['class' => 'control-label']) !!}
        
        <div class="input-group voice-preview">
            {!! Form::select('stt_profile_id', $stt_profiles, old('stt_profile_id', optional($aiBot)->stt_profile_id), [
                'class' => 'form-control',
                'required' => false,
                'placeholder' => __('Select STT Profile'),
            ]) !!}
        </div>
        @error('stt_profile_id')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<!-- <div class="col-lg-6">
    <div class="form-group @error('Internal Directory') has-error @enderror">
        {!! Form::label('internal_directory', __('Internal Directory'), ['class' => 'control-label']) !!}

        {!! Form::number(
            'internal_directory',
            old('internal_directory', optional($aiBot)->internal_directory),
            [
                'class' => 'form-control' . ($errors->has('internal_directory') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter internal directory here...'),
            ],
        ) !!}
        @error('internal_directory')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div> -->


<div class="col-lg-12">
<div class="form-group @error('resource') has-error @enderror">
    {!! Form::label('resource',__('Assistant Instructions'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::textarea('resource',old('resource', optional($aiBot)->resource), ['class' => 'form-control' . ($errors->has('resource') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '4294967295', 'required' => true, 'placeholder' => __('Enter resource here...'), ]) !!}
        @error('resource') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('max_interactions') has-error @enderror">
        {!! Form::label('max_interactions', __('Max Interactions'), ['class' => 'control-label']) !!}

        {!! Form::number(
            'max_interactions',
            old('max_interactions', optional($aiBot)->max_interactions ? optional($aiBot)->max_interactions : 300),
            [
                'class' => 'form-control' . ($errors->has('max_interactions') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter max interactions timeout here...'),
            ],
        ) !!}
        @error('max_interactions')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('max_silince') has-error @enderror">
        {!! Form::label('max_silince', __('Max Silence'), ['class' => 'control-label']) !!}

        {!! Form::number(
            'max_silince',
            old('max_silince', optional($aiBot)->max_silince ? optional($aiBot)->max_silince : 3),
            [
                'class' => 'form-control' . ($errors->has('max_silince') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter max silince timeout here...'),
            ],
        ) !!}
        @error('max_silince')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('email') has-error @enderror">
        {!! Form::label('email',__('Email Address to Notify'),['class' => 'control-label']) !!}
    
            {!! Form::text('email',old('email', optional($aiBot)->email), ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter email here...'), ]) !!}
            @error('email') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('create_support_ticket') has-error @enderror">
        {!! Form::label('create_support_ticket',__('Create Support Ticket'),['class' => 'control-label']) !!}
    
            <div class="checkbox">
                <label for='create_support_ticket_1'>
                    {!! Form::checkbox('create_support_ticket', '1',  (old('create_support_ticket', optional($aiBot)->create_support_ticket) == '1' ? true : null) , ['id' => 'create_support_ticket_1', 'class' => ''  . ($errors->has('create_support_ticket') ? ' is-invalid' : null), ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('create_support_ticket') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>

    <div class="col-lg-6">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __("Fallback Destination if AI Doesn't Understand"), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($aiBot->function->func) ? $aiBot->function->func : '';
            @endphp

            {!! Form::select('function_id', $functions, old('function_id', $func), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Select Module'),
            ]) !!}
            @error('function_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('destination_id') has-error @enderror">
            {!! Form::label('destination_id', '&nbsp;', ['class' => 'control-label']) !!}
            
            {!! Form::select(
                'destination_id',
                $destinations,
                old('destination_id', optional($aiBot)->destination_id),
                ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select destination')],
            ) !!}
            @error('destination_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif

@push('script')
    <!-- <script src="{{ asset('js/index.js') }}"></script> -->
    <script src="{{ asset('js/play.js') }}"></script>

    <script>
        $(document).ready(function() {
			      // $crud = $('#crud_contents').crud();

            $(document).on("change", "#provider", function(event){
                event.preventDefault();
                console.log($(this).val());

                if($(this).val() == 'others'){
                  $("#api_endpoint").removeClass('d-none');
                }
                else{
                  $("#api_endpoint").addClass('d-none');
                } 
            });
            
            destinations = "{{ route('ai_bots.ai_bot.destinations', 0) }}"

            $(document).on('change', '#function_id', function(e) {
                e.preventDefault()

                var val = $(this).val().trim()

                if (val != undefined && val != '') {
                    route = destinations.trim().slice(0, -1) + val
                    console.log(route)

                    $.get(route, function(res) {
                        console.log(res)
                        $("#destination_id").html(res)
                    })

                } else
                    $("#destination_id").html('<option> Select destination </option>')

            })

        })
    </script>
@endpush