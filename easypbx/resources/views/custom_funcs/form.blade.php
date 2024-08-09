@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

@push('css')

<link rel="stylesheet" href="{{ asset('js/codemirror/lib/codemirror.css') }}">
<link rel="stylesheet" href="{{ asset('js/codemirror/addon/hint/show-hint.css') }}">
<link rel="stylesheet" href="{{ asset('js/codemirror/theme/neonsyntax.css') }}">


<style type="text/css">
    .CodeMirror {
        font-size: 15px;
        width: 100%, ;
        height: 100%;
        resize: vertical;
    }

</style>

@endpush

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('name',old('name', optional($customFunc)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null),  'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

{!! Form::hidden('func_lang', old('func_lang', optional($customFunc)->func_lang),['id'=>'func_lang']) !!}
      

<!-- <div class="col-lg-12">
    <div class="form-group @error('func_lang') has-error @enderror">
        {!! Form::label('func_lang',__('Function Language'),['class' => 'control-label']) !!}
        <span class="text-required">*</span>

            {!! Form::select('func_lang',config('enums.func_langs'), old('func_lang', optional($customFunc)->func_lang), ['class' => 'form-control', 'required' => true,  ]) !!}
            @error('func_lang') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>
 -->

<div class="col-lg-12">
<div class="form-group @error('func_body') has-error @enderror">
    {!! Form::label('func_body',__('Function Body'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::hidden('func_body', old('func_body', optional($customFunc)->func_body), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Enter func body here...'), ]) !!}
        @error('func_body') <p class="help-block  text-danger"> {{ $message }} </p> @enderror

        {!! Form::text('func_body_text', old('func_body', optional($customFunc)->func_body), ['id'=>'url','class' => ' form-control','rows'=>"4",  'placeholder' => __('Enter url here...'), ]) !!}
       
        {!! Form::textarea('func_body_area', old('func_body', optional($customFunc)->func_body), ['id'=>'code','rows'=>"4",'style'=>'height:100px;','class' => 'form-control d-none', ]) !!}
       
       
</div>
</div>

</div>

<textarea id="php_block" class="d-none">
&lt;?php  

    $response-&gt;say('test voice');  
    
?&gt;
</textarea>

<textarea id="xml_block" class="d-none">
&lt;?xml version="1.0"?&gt;
&lt;response&gt;
&lt;say&gt;Please enter the extension number  followed by the hash key&lt;/say&gt;
&lt;/response&gt;
</textarea>


@push('script')

<script src="{{ asset('js/codemirror/lib/codemirror.js') }}"></script>
<script src="{{ asset('js/codemirror/addon/hint/show-hint.js') }}"></script>
<script src="{{ asset('js/codemirror/addon/hint/xml-hint.js') }}"></script>
<script src="{{ asset('js/codemirror/addon/hint/html-hint.js') }}"></script>
<script src="{{ asset('js/codemirror/mode/xml/xml.js') }}"></script>
<script src="{{ asset('js/codemirror/mode/javascript/javascript.js') }}"></script>
<script src="{{ asset('js/codemirror/mode/css/css.js') }}"></script>
<script src="{{ asset('js/codemirror/mode/clike/clike.js') }}"></script>
<script src="{{ asset('js/codemirror/mode/php/php.js') }}"></script>
<script src="{{ asset('js/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>
<script src="{{ asset('js/codemirror/addon/selection/active-line.js') }}"></script>
<script src="{{ asset('js/codemirror/addon/edit/matchbrackets.js') }}"></script>

<script type="text/javascript">

		CodeMirror.commands.autocomplete = function(cm) {
			CodeMirror.showHint(cm, CodeMirror.hint.html);
		}
		window.onload = function() {
            
			editor = CodeMirror.fromTextArea(document.getElementById("code"), {
				mode: "application/x-httpd-php",
				theme: "neonsyntax",
				lineWrapping: true,
				lineNumbers: true,
				styleActiveLine: true,
				matchBrackets: true,

				extraKeys: {
					"Ctrl-Space": "autocomplete"
				},
				value: ""
			});

            editor.setSize(null, 200);
          
            $('#func_lang').change(function(){
                console.log($(this).val());
                if($(this).val() == 0){
                    $(editor.getWrapperElement()).hide();
                    $('#url').show();
                }else{
                    $('#url').hide();
                    console.log($(editor.getWrapperElement()).val());
                    if(editor.getValue() == '') {
                        val  = $('#php_block').val();
                        if($(this).val() == 1) val = $('#xml_block').val();
                        editor.setValue(val);
                       // editor.refresh();
                       
                    }
                    $(editor.getWrapperElement()).show();
                    editor.refresh();
                }
            });

            $('#func_lang').trigger('change');
            //var cm = $('.CodeMirror')[0].CodeMirror;

            $( ".custom_func_form" ).on( "submit", function( event ) {
                if($('#func_lang').val() == 0)
                    $('#func_body').val($('#url').val());
                else
                    $('#func_body').val($('#code').val());
                //alert( "Handler for `submit` called." );
               // console.log($('#code').val());
               // event.preventDefault();
            });
            

		};
</script>
@endpush

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


