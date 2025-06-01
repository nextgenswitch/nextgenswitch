@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">



<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('name',old('name', optional($user)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
    <div class="form-group @error('email') has-error @enderror">
        {!! Form::label('email',__('Email'),['class' => 'control-label']) !!}
        <span class="text-required">*</span>
      
    {!! Form::text('email',old('email', optional($user)->email), ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter email here...'), ]) !!}
            @error('email') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
    </div>


<div class="col-lg-12">
<div class="form-group @error('password') has-error @enderror">
    @php 
        $pass_label = $user ? __('Password (To change password, enter new password)') : __('Password');
    @endphp

    {!! Form::label('password', $pass_label, ['class' => 'control-label']) !!}
  
    {!! Form::text('password',old('password', optional($user)->password), ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => false, 'placeholder' => __('Enter password here...'), ]) !!}
        @error('password') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

@if(!$user || $user->id != auth()->user()->id)
<div class="col-lg-12">
<div class="form-group @error('role') has-error @enderror">
    {!! Form::label('role',__('Role'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::select('role', config('enums.user_roles') ,old('role', optional($user)->role), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select role'), ]) !!}
        @error('role') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>
@else
{!! Form::hidden('role',$user->role) !!}
@endif

<div class="col-lg-6">
    <div class="form-group @error('status') has-error @enderror">
        {!! Form::label('status', __('Active?'),['class' => 'control-label']) !!}
    
            <div class="checkbox">
                <label for='status'>
                    {!! Form::checkbox('status', '1',  (old('status', optional($user)->status) == '1' ? true : null) , ['id' => 'status', 'class' => ''  . ($errors->has('status') ? ' is-invalid' : null), ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>
    
            @error('status') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
    </div>



</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


