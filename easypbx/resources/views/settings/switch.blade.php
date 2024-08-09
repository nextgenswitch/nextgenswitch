@php 
    $default = [
        'udp_listen'=>'0.0.0.0:5060',
        'tcp_listen'=>'0.0.0.0:5060',
        'tls_listen'=>'0.0.0.0:5061',
        'rtp_start'=>'10000',
        'rtp_end'=>'20000',
        'http_listen'=>'127.0.0.1:5001',
    ];
    $settings = array_merge( $default,$settings); 
    //dd($settings);
@endphp
@foreach ($settings as $key => $val)                            
                <div class="row">
                    <div class="col-md-4 text-right">
                        {!! Form::label($key, __(ucwords(str_replace('_', ' ', $key))),['class' => 'control-label']) !!}
                        <span class="text-required">*</span>
                    </div>
    
                    <div class="col-md-8">
                        <div class="form-group @error('name') has-error @enderror">
                            
                            {!! Form::text("settings[{$key}]", $val, ['class' => 'form-control' . ($errors->has($key) ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter '. ucwords(str_replace('_', ' ', $key)) .' here...'), ]) !!}
                            @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
                        </div>
                    </div>
                </div>
@endforeach