@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" enctype="multipart/form-data" class="form-horizontal">
@csrf
@endif

<div class="row">



<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('name',old('name', optional($voiceFile)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
    <div class="form-group">
        {!! Form::label('voice_type', __('Voice Type'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>

        {!! Form::select('voice_type', [0 => 'Voice file', 1 => 'Text to speech'], old('voice_type'), [ 'class' => 'form-control']) !!}

    </div>
</div>



<div class="col-lg-12" id="voice-file">
<div class="form-group @error('file_name') has-error @enderror">
    {!! Form::label('file_name',__('Voice File'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>

        {!! Form::file('file',  ['class' => 'form-control' . ($errors->has('file_name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => false, 'placeholder' => __('place_voice_file'),'accept'=>".mp3,.wav" ]) !!}
        @error('file_name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


<div class="col-lg-12 tts @if (isset($voiceFile->file_name)) d-none @endif">
    <div class="form-group @error('tts_text') has-error @enderror">
        {!! Form::label('tts_text', __('Text to speech Content'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>

        {!! Form::textarea('tts_text', old('tts_text', optional($voiceFile)->tts_text), [
            'class' => 'form-control char-count' . ($errors->has('tts_text') ? ' is-invalid' : null),
            'minlength' => '1',
            'maxlength' => '2024',
            'placeholder' => __('Enter text content here'),
        ]) !!}
        
        @error('tts_text')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror

        <span class="float-right content-info"></span>
        <!-- <span class="float-right voice-preview"><a href="  "> {{ __('Voice Preview') }} </a></span> -->

    </div>
</div>

<div class="col-lg-12 tts @if (isset($voiceFile->file_name)) d-none @endif">
    <div class="form-group @error('tts_profile_id') has-error @enderror">
        {!! Form::label('tts_profile_id', __('TTS Profile'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>

                        {!! Form::select('tts_profile_id', $tts_profiles, old('tts_profile_id', optional($voiceFile)->tts_profile_id), [
                            'class' => 'form-control',
                            'data-live-search' => true,
                        ]) !!}
        @error('tts_profile_id')
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

<script>

    $(document).ready(function(){
    
        $(document).on('change', '#voice_type', function(event){

            if($(this).val() == 1){
                $('.tts').each((index, item) => {
                    $(item).removeClass('d-none')
                })

                $("#voice-file").addClass('d-none')
            }

            else{
                $('.tts').each((index, item) => {
                    $(item).addClass('d-none')
                })

                $("#voice-file").removeClass('d-none')
            }
        })


        $('#voice_type').trigger('change')
    })

</script>
@endpush
