@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
  
{!! Form::text('name',old('name', optional($voiceRecord)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('voice_id') has-error @enderror">
    {!! Form::label('voice_id',__('Announcement'),['class' => 'control-label']) !!}
  
        {!! Form::select('voice_id',$voices,old('voice_id', optional($voiceRecord)->voice_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select voice'), ]) !!}
        @error('voice_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>
<div class="col-lg-12">
    <div class="form-group @error('play_beep') has-error @enderror">
        {!! Form::label('play_beep',__('Play Beep Before Record'),['class' => 'control-label']) !!}
    
            <div class="checkbox">
                <label for='play_beep_1'>
                    {!! Form::checkbox('play_beep', '1',  (old('play_beep', optional($voiceRecord)->play_beep) == '1' ? true : null) , ['id' => 'play_beep_1', 'class' => ''  . ($errors->has('play_beep') ? ' is-invalid' : null), ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('play_beep') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>

<div class="col-lg-12">
    <div class="form-group @error('is_create_ticket') has-error @enderror">
        {!! Form::label('is_create_ticket',__('Create Support Ticket'),['class' => 'control-label']) !!}
    
            <div class="checkbox">
                <label for='is_create_ticket_1'>
                    {!! Form::checkbox('is_create_ticket', '1',  (old('is_create_ticket', optional($voiceRecord)->is_create_ticket) == '1' ? true : null) , ['id' => 'is_create_ticket_1', 'class' => ''  . ($errors->has('is_create_ticket') ? ' is-invalid' : null), ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('is_create_ticket') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>

<div class="col-lg-12">
<div class="form-group @error('is_transcript') has-error @enderror">
    {!! Form::label('is_transcript',__('Auto Transcribe Recorded Voice(Need STT Profile)'),['class' => 'control-label']) !!}
  
        <div class="checkbox">
            <label for='is_transcript_1'>
                {!! Form::checkbox('is_transcript', '1',  (old('is_transcript', optional($voiceRecord)->is_transcript) == '1' ? true : null) , ['id' => 'is_transcript_1', 'class' => ''  . ($errors->has('is_transcript') ? ' is-invalid' : null), ]) !!}
                {{ __('Yes') }}
            </label>
        </div>

        @error('is_transcript') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


<div class="col-lg-12">
<div class="form-group @error('email') has-error @enderror">
    {!! Form::label('email',__('Email Address to Notify'),['class' => 'control-label']) !!}
  
        {!! Form::text('email',old('email', optional($voiceRecord)->email), ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter email here...'), ]) !!}
        @error('email') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('phone') has-error @enderror">
    {!! Form::label('phone',__('Phone no to Notify by SMS'),['class' => 'control-label']) !!}
  
        {!! Form::text('phone',old('phone', optional($voiceRecord)->phone), ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter phone here...'), ]) !!}
        @error('phone') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


