@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="form-group @error('name') has-error @enderror">
            {!! Form::label('name', __('Name'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('name', old('name', optional($ivr)->name), [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '255',
                'required' => true,
                'placeholder' => __('Enter name here...'),
            ]) !!}
            @error('name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('welcome_voice') has-error @enderror">
            {!! Form::label('welcome_voice', __('welcome voice'), ['class' => 'control-label']) !!}

            <div class="input-group voice-preview">
                {!! Form::select('welcome_voice', $voiceFiles, old('welcome_voice', optional($ivr)->welcome_voice), [
                    'class' => 'form-control selectpicker',
                    'data-live-search' => true,
                    'placeholder' => __('Nothing selected'),
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
            @error('welcome_voice')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-12">
        <div class="form-group @error('instruction_voice') has-error @enderror">
            {!! Form::label('instruction_voice', __('Instruction voice'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            <div class="input-group voice-preview">
                {!! Form::select('instruction_voice', $voiceFiles, old('instruction_voice', optional($ivr)->instruction_voice), [
                    'class' => 'form-control selectpicker',
                    'data-live-search' => true,
                    'required' => true,
                    'placeholder' => __('Nothing selected'),
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
            @error('instruction_voice')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('invalid_voice') has-error @enderror">
            {!! Form::label('invalid_voice', __('Invalid voice'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            <div class="input-group voice-preview">
                {!! Form::select('invalid_voice', $voiceFiles, old('invalid_voice', optional($ivr)->invalid_voice), [
                    'class' => 'form-control selectpicker',
                    'data-live-search' => true,
                    'required' => true,
                    'placeholder' => __('Nothing selected'),
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
            @error('invalid_voice')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('timeout_voice') has-error @enderror">
            {!! Form::label('timeout_voice', __('Timeout voice'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            <div class="input-group voice-preview">
                {!! Form::select('timeout_voice', $voiceFiles, old('timeout_voice', optional($ivr)->timeout_voice), [
                    'class' => 'form-control selectpicker',
                    'data-live-search' => true,
                    'required' => true,
                    'placeholder' => __('Nothing selected'),
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
            @error('timeout_voice')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('timeout') has-error @enderror">
            {!! Form::label('timeout', __('Timeout'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('timeout', old('timeout', optional($ivr)->timeout), [
                'class' => 'form-control' . ($errors->has('timeout') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => true,
                'placeholder' => __('Enter timeout seconds here...'),
            ]) !!}

            @error('timeout')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12 column">
        <div class="form-group @error('mode') has-error @enderror">
            <label for="ivr_mode" class="control-label">{{ __('IVR Mode') }}</label>
            {!! Form::select('mode', config('enums.ivr_mode'), old('mode', optional($ivr)->mode), [
                'class' => 'form-control mode',
                'required' => true,
                'id' => 'ivr_mode',
            ]) !!}
            @error('mode')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


<div class="modal fade" id="promptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">LLM instruction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>You’re a LLM that detects intent from user queries. Your task is to classify the user's intent based on their query. Below are the possible intents with brief descriptions. Use these to accurately determine the user's goal, and output only the intent topic.
   <ul>
    <li>Order Status: Inquiries about the current status of an order, including delivery tracking and estimated arrival times.</li>
    <li>Product Information: Questions regarding product details, specifications, availability, or compatibility.</li>
    <li>Payments: Queries related to making payments, payment methods, billing issues, or transaction problems.</li>
    <li>Returns: Requests or questions about returning a product, including return policies and procedures.</li>
    <li>Feedback: User comments, reviews, or general feedback about products, services, or experiences.</li>
<li>Other: Choose this if the query doesn’t fall into any of the other intents.</li>   
</ul>
</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

    <div class="col-lg-12 @if (!optional($ivr)->mode) ) d-none @endif" id="inent_analyzer_div">
        <div class="form-group @error('intent_analyzer') has-error @enderror">
            {!! Form::label('intent_analyzer', __('Speech Intent Analysis'), ['class' => 'control-label']) !!}

            {!! Form::textarea('intent_analyzer', old('intent_analyzer', optional($ivr)->intent_analyzer), [
                'class' => 'form-control' . ($errors->has('intent_analyzer') ? ' is-invalid' : null),
                'minlength' => '1',
                'required' => false,
                'placeholder' => __('Enter LLM instruction properly to parse intent from the voice text.  '),
            ]) !!}
            <small id="emailHelp" class="form-text text-muted">Make sure you have default LLM setup and Speech to Text added in AI providers . To see a example intent instruct <a href="#" data-toggle="modal" data-target="#promptModal" >click here </a>. </small>
            @error('intent_analyzer')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-12">
        <div class="form-group @error('end_key') has-error @enderror">
            {!! Form::label('end_key', __('End Key'), ['class' => 'control-label']) !!}

            {!! Form::select('end_key', ['#' => '#', '*' => '*'], old('end_key', optional($ivr)->end_key), [
                'class' => 'form-control',
            ]) !!}
            @error('end_key')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-12">
        <div class="form-group @error('max_digit') has-error @enderror">
            {!! Form::label('max_digit', __('Max Digit'), ['class' => 'control-label']) !!}

            {!! Form::number('max_digit', old('max_digit', optional($ivr)->max_digit ? optional($ivr)->max_digit : 1), [
                'class' => 'form-control' . ($errors->has('max_digit') ? ' is-invalid' : null),
                'min' => '0',
                'required' => false,
                'max' => '2147483647',
                'placeholder' => __('Enter digit here...'),
            ]) !!}
            @error('max_digit')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('max_retry') has-error @enderror">
            {!! Form::label('max_retry', __('Max Retry'), ['class' => 'control-label']) !!}

            {!! Form::number('max_retry', old('max_retry', optional($ivr)->max_retry ? optional($ivr)->max_retry : 3), [
                'class' => 'form-control' . ($errors->has('max_retry') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter max retry here...'),
            ]) !!}
            @error('max_retry')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6 column">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Invalid Destination'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($ivr->func->func) ? $ivr->func->func : '';
            @endphp
            {!! Form::select('function_id', $functions, old('function_id', $func), [
                'class' => 'form-control function_id',
                'required' => true,
                'placeholder' => __('Select Module'),
            ]) !!}
            @error('function_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6 column">
        <div class="form-group @error('destination_id') has-error @enderror">
            <label for="" class="control-label">&nbsp;</label>


            {!! Form::select('destination_id', $ivrDestinations, old('destination_id', optional($ivr)->destination_id), [
                'class' => 'form-control destination_id',
                'required' => true,
                'placeholder' => __('Select destination'),
            ]) !!}
            @error('destination_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>



</div>

@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>



    <script type="text/javascript">
        $(document).ready(function() {
            $('.selectpicker').selectpicker();

            $(document).on('change', '#welcome_voice', function() {
                var val = $(this).val();

                $(".tts").each((index, item) => {

                    if (val > 0) {
                        $(item).addClass('d-none');
                    } else {
                        $(item).removeClass('d-none');
                    }

                })


            })
        });
    </script>
@endif


@push('script')
    <script src="{{ asset('js/func_destination.js') }}"></script>
    <script src="{{ asset('js/play.js') }}"></script>
@endpush
