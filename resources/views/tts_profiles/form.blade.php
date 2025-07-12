@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" enctype="multipart/form-data" accept-charset="UTF-8"
        id="create_form" name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">

    {!! Form::hidden('type', old('type', optional($ttsProfile)->type)) !!}

    <div class="col-lg-12">
        <div class="form-group @error('name') has-error @enderror">
            {!! Form::label('name', __('Name'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('name', old('name', optional($ttsProfile)->name), [
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
    <!--
    <div class="col-lg-12">
        <div class="form-group @error('organization_id') has-error @enderror">
            {!! Form::label('organization_id', __('Organization'), ['class' => 'control-label']) !!}


            {!! Form::select(
                'organization_id',
                $organizations,
                old('organization_id', optional($ttsProfile)->organization_id),
                [
                    'class' => 'form-control selectpicker',
                    'data-live-search' => 'true',
                    'placeholder' => __('All Organization'),
                ],
            ) !!}

            @error('organization_id')
    <p class="help-block  text-danger"> {{ $message }} </p>
@enderror
        </div>
    </div> -->



    <div class="col-lg-12">
        <div class="form-group @error('provider') has-error @enderror">
            {!! Form::label('provider', __('Providers'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            @php
                if (isset($ttsProfile->type) && $ttsProfile->type == 0) {
                    $providers = config('enums.tts_providers');
                }
                if (isset($ttsProfile->type) && $ttsProfile->type == 1) {
                    $providers = config('enums.stt_providers');
                }
                if (isset($ttsProfile->type) && $ttsProfile->type == 2) {
                    $providers = config('enums.llm_providers');
                }

            @endphp
            {!! Form::select('provider', $providers, old('provider', optional($ttsProfile)->provider), [
                'class' => 'form-control',
            ]) !!}

            @error('provider')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    @php
        $gtts = $ttsProfile->gtts ?? null;
    @endphp

    <div class="col-12 {{ $gtts ? 'd-blone' : 'd-none' }}" id="config_gtts">
        <div class="card mb-2">
            <div class="card-header"> {{ __('Google Text To Speech(gTTS) Configuration') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group @error('gtts.url') has-error @enderror">
                            {!! Form::label('gtts_url', __('URL'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::url('gtts[url]', old('gtts.url', optional($gtts)->url), [
                                'class' => 'form-control' . ($errors->has('gtts.url') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'gtts.url',
                                'required' => false,
                                'placeholder' => __('Enter url here...'),
                            ]) !!}

                            @error('gtts.url')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $witai = $ttsProfile->witai ?? null;
    @endphp

    <div class="col-12 {{ $witai ? 'd-block' : 'd-none' }}" id="config_witai">
        <div class="card mb-2">
            <div class="card-header"> {{ __('WitAi Configuration') }}</div>
            <div class="card-body">
                <div class="row">


                    <div class="col-lg-12">
                        <div class="form-group @error('witai.api_key') has-error @enderror">
                            {!! Form::label('witai_api_key', __('API Key'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('witai[api_key]', old('witai.api_key', optional($witai)->api_key), [
                                'class' => 'form-control' . ($errors->has('witai.api_key') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'witai_api_key',
                                'required' => false,
                                'placeholder' => __('Enter api key here...'),
                            ]) !!}

                            @error('witai.api_key')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group @error('witai.api_version') has-error @enderror">
                            {!! Form::label('witai_api_version', __('API Version'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('witai[api_version]', old('witai.api_version', optional($witai)->api_version), [
                                'class' => 'form-control' . ($errors->has('witai.api_version') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'witai_api_version',
                                'required' => false,
                                'placeholder' => __('Enter api version here...'),
                            ]) !!}

                            @error('witai.api_version')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    @php
        $microsoft_azure = $ttsProfile->microsoft_azure ?? null;
    @endphp

    <div class="col-12 {{ $microsoft_azure ? 'd-block' : 'd-none' }}" id="config_microsoft_azure">
        <div class="card mb-2">
            <div class="card-header"> {{ __('Microsoft Azure Configuration') }}</div>
            <div class="card-body">
                <div class="row">


                    <div class="col-lg-12">
                        <div class="form-group @error('microsoft_azure.api_key') has-error @enderror">
                            {!! Form::label('microsoft_azure_api_key', __('API Key'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('microsoft_azure[api_key]', old('microsoft_azure.api_key', optional($microsoft_azure)->api_key), [
                                'class' => 'form-control' . ($errors->has('microsoft_azure.api_key') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'microsoft_azure_api_key',
                                'required' => false,
                                'placeholder' => __('Enter api key here...'),
                            ]) !!}

                            @error('microsoft_azure.api_key')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group @error('microsoft_azure.region') has-error @enderror">
                            {!! Form::label('microsoft_azure_region', __('Region'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('microsoft_azure[region]', old('microsoft_azure.region', optional($microsoft_azure)->region), [
                                'class' => 'form-control' . ($errors->has('generic.region') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'microsoft_azure_region',
                                'required' => false,
                                'placeholder' => __('Enter region here...'),
                            ]) !!}

                            @error('microsoft_azure.region')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    @php
        $generic = $ttsProfile->generic ?? null;
    @endphp

    <div class="col-12 {{ $generic ? 'd-block' : 'd-none' }}" id="config_generic">
        <div class="card mb-2">
            <div class="card-header"> {{ __('Generic Configuration') }}</div>
            <div class="card-body">
                <div class="row">


                    <div class="col-lg-12">
                        <div class="form-group @error('generic.api_key') has-error @enderror">
                            {!! Form::label('generic_api_key', __('API Key'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('generic[api_key]', old('generic.api_key', optional($generic)->api_key), [
                                'class' => 'form-control' . ($errors->has('generic.api_key') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'generic_api_key',
                                'required' => false,
                                'placeholder' => __('Enter api key here...'),
                            ]) !!}

                            @error('generic.api_key')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group @error('generic.api_endpoint') has-error @enderror">
                            {!! Form::label('generic_api_endpoint', __('API Endpoint'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('generic[api_endpoint]', old('generic.api_endpoint', optional($generic)->api_endpoint), [
                                'class' => 'form-control' . ($errors->has('generic.api_endpoint') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'generic_api_endpoint',
                                'required' => false,
                                'placeholder' => __('Enter api endpoint here...'),
                            ]) !!}

                            @error('generic.api_endpoint')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="col-12 {{ isset($ttsProfile->provider) && $ttsProfile->provider == 'google_cloud' ? 'd-block' : 'd-none' }}"
        id="config_google_cloud">
        <div class="card mb-2">
            <div class="card-header"> {{ __('Google Cloud Text To Speech Configuration') }}</div>
            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="form-group @error('google_cloud.config') has-error @enderror">
                            {!! Form::label('google_cloud_json', __('Credentials Json File content'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::textarea('google_cloud[config]', old('google_cloud.config', optional($ttsProfile)->config), [
                                'class' => 'form-control char-count' . ($errors->has('google_cloud.config') ? ' is-invalid' : null),
                                'required' => false,
                                'id' => 'google_cloud_json',
                                'placeholder' => __('Put credentials Json file content here...'),
                            ]) !!}

                            @error('google_cloud.config')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @php
        $amazon_polly = $ttsProfile->amazon_polly ?? null;
    @endphp

    <div class="col-12 {{ $amazon_polly ? 'd-block' : 'd-none' }}" id="config_amazon_polly">
        <div class="card mb-2">
            <div class="card-header"> {{ __('Amazon Polly Configuration') }}</div>
            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="form-group @error('amazon_polly.aws_access_key_id') has-error @enderror">
                            {!! Form::label('aws_access_key_id', __('Access Key ID'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text(
                                'amazon_polly[aws_access_key_id]',
                                old('amazon_polly.aws_access_key_id', optional($amazon_polly)->aws_access_key_id),
                                [
                                    'class' => 'form-control' . ($errors->has('amazon_polly.aws_access_key_id') ? ' is-invalid' : null),
                                    'minlength' => '1',
                                    'maxlength' => '255',
                                    'id' => 'aws_access_key_id',
                                    'required' => false,
                                    'placeholder' => __('Enter aws access key  ID here...'),
                                ],
                            ) !!}

                            @error('amazon_polly.aws_access_key_id')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group @error('amazon_polly.aws_secret_access_key') has-error @enderror">
                            {!! Form::label('aws_access_key_id', __('Secret Access Key'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text(
                                'amazon_polly[aws_secret_access_key]',
                                old('amazon_polly.aws_secret_access_key', optional($amazon_polly)->aws_secret_access_key),
                                [
                                    'class' => 'form-control' . ($errors->has('amazon_polly.aws_secret_access_key') ? ' is-invalid' : null),
                                    'minlength' => '1',
                                    'maxlength' => '255',
                                    'id' => 'aws_secret_access_key',
                                    'required' => false,
                                    'placeholder' => __('Enter aws secret access key  here...'),
                                ],
                            ) !!}

                            @error('amazon_polly.aws_secret_access_key')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group @error('amazon_polly.aws_default_region') has-error @enderror">
                            {!! Form::label('aws_access_key_id', __('Default Region'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text(
                                'amazon_polly[aws_default_region]',
                                old('amazon_polly.aws_default_region', optional($amazon_polly)->aws_default_region),
                                [
                                    'class' => 'form-control' . ($errors->has('amazon_polly.aws_default_region') ? ' is-invalid' : null),
                                    'minlength' => '1',
                                    'maxlength' => '255',
                                    'id' => 'aws_default_region',
                                    'required' => false,
                                    'placeholder' => __('Enter aws default region here...'),
                                ],
                            ) !!}

                            @error('amazon_polly.aws_default_region')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    @php
        $openai = $ttsProfile->openai ?? null;
    @endphp

    <div class="col-12 {{ $openai ? 'd-blone' : 'd-none' }}" id="config_openai">
        <div class="card mb-2">
            <div class="card-header"> {{ __('OpenAI Configuration') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group @error('openai.api_key') has-error @enderror">
                            {!! Form::label('openai_api_key', __('API Key'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('openai[api_key]', old('openai.api_key', optional($openai)->api_key), [
                                'class' => 'form-control' . ($errors->has('openai.api_key') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'openai_api_key',
                                'required' => false,
                                'placeholder' => __('Enter api key here...'),
                            ]) !!}

                            @error('openai.api_key')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $gemini = $ttsProfile->gemini ?? null;
    @endphp

    <div class="col-12 {{ $gemini ? 'd-blone' : 'd-none' }}" id="config_gemini">
        <div class="card mb-2">
            <div class="card-header"> {{ __('Gemini Configuration') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group @error('gemini.api_key') has-error @enderror">
                            {!! Form::label('gemini_api_key', __('API Key'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('gemini[api_key]', old('gemini.api_key', optional($gemini)->api_key), [
                                'class' => 'form-control' . ($errors->has('gemini.api_key') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'gemini_api_key',
                                'required' => false,
                                'placeholder' => __('Enter api key here...'),
                            ]) !!}

                            @error('gemini.api_key')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $groq = $ttsProfile->groq ?? null;
    @endphp

    <div class="col-12 {{ $groq ? 'd-block' : 'd-none' }}" id="config_groq">
        <div class="card mb-2">
            <div class="card-header"> {{ __('Groq Configuration') }}</div>
            <div class="card-body">
                <div class="row">


                    <div class="col-lg-12">
                        <div class="form-group @error('groq.api_key') has-error @enderror">
                            {!! Form::label('groq', __('API Key'), ['class' => 'control-label']) !!}
                            <span class="text-required">*</span>

                            {!! Form::text('groq[api_key]', old('groq.api_key', optional($groq)->api_key), [
                                'class' => 'form-control' . ($errors->has('groq.api_key') ? ' is-invalid' : null),
                                'minlength' => '1',
                                'maxlength' => '255',
                                'id' => 'groq',
                                'required' => false,
                                'placeholder' => __('Enter api key here...'),
                            ]) !!}

                            @error('cloudflare.api_key')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>

    @if (optional($ttsProfile)->type == 1 || optional($ttsProfile)->type == 2)
        @php
            $cloudflare = $ttsProfile->cloudflare ?? null;
        @endphp

        <div class="col-12 {{ $cloudflare ? 'd-block' : 'd-none' }}" id="config_cloudflare">
            <div class="card mb-2">
                <div class="card-header"> {{ __('Cloudflare Configuration') }}</div>
                <div class="card-body">
                    <div class="row">


                        <div class="col-lg-12">
                            <div class="form-group @error('cloudflare.api_key') has-error @enderror">
                                {!! Form::label('cloudflare', __('API Key'), ['class' => 'control-label']) !!}
                                <span class="text-required">*</span>

                                {!! Form::text('cloudflare[api_key]', old('cloudflare.api_key', optional($cloudflare)->api_key), [
                                    'class' => 'form-control' . ($errors->has('cloudflare.api_key') ? ' is-invalid' : null),
                                    'minlength' => '1',
                                    'maxlength' => '255',
                                    'id' => 'cloudflare',
                                    'required' => false,
                                    'placeholder' => __('Enter api key here...'),
                                ]) !!}

                                @error('cloudflare.api_key')
                                    <p class="help-block  text-danger"> {{ $message }} </p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group @error('cloudflare.bearer_token') has-error @enderror">
                                {!! Form::label('cloudflare_bearer_token', __('Bearer Token'), ['class' => 'control-label']) !!}
                                <span class="text-required">*</span>

                                {!! Form::text('cloudflare[bearer_token]', old('cloudflare.bearer_token', optional($cloudflare)->bearer_token), [
                                    'class' => 'form-control' . ($errors->has('cloudflare.bearer_token') ? ' is-invalid' : null),
                                    'minlength' => '1',
                                    'maxlength' => '255',
                                    'id' => 'cloudflare_bearer_token',
                                    'required' => false,
                                    'placeholder' => __('Enter bearer token version here...'),
                                ]) !!}

                                @error('cloudflare.bearer_token')
                                    <p class="help-block  text-danger"> {{ $message }} </p>
                                @enderror
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    @endif



    <div class="col-lg-12">
        <div class="form-group @error('language') has-error @enderror">
            {!! Form::label('language', __('Language'), ['class' => 'control-label']) !!}
            <!-- <span class="text-required">*</span> -->

            {!! Form::select('language', config('enums.tts_languages'), old('language', optional($ttsProfile)->language), [
                'class' => 'form-control selectpicker',
                'data-live-search' => 'true',
                'placeholder' => __('Nothing selected'),
            ]) !!}

            @error('language')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('model') has-error @enderror">
            {!! Form::label('model', __('Model'), ['class' => 'control-label']) !!}
            <!-- <span class="text-required">*</span> -->

            {!! Form::text('model', old('model', optional($ttsProfile)->model), [
                'class' => 'form-control' . ($errors->has('model') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '255',
                'required' => false,
                'placeholder' => __('Enter model here...'),
            ]) !!}
            @error('model')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('record') has-error @enderror">
            {!! Form::label('is_default', __('Default ?'), ['class' => 'control-label']) !!}

            <div class="checkbox">

                {!! Form::checkbox(
                    'is_default',
                    '1',
                    old('is_default', isset($ttsProfile->is_default) && $ttsProfile->is_default == 1 ? true : null),
                    ['id' => 'is_default', 'class' => '' . ($errors->has('is_default') ? ' is-invalid' : null)],
                ) !!}
                {{ __('Yes') }}

            </div>

            @error('status')
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

            setTimeout(() => {
                $(document).find('#provider').trigger("change")
            }, 1000);

            /*  var providers = [
                 'gtts',
                 'witai',
                 'google_cloud',
                 'amazon_polly',
                 'cloudflare',
                 'openai',
                 'generic',
                 'microsoft_azure',
                 'groq'
             ]; */

            @php
                $providervar = json_encode(array_keys($providers));
            @endphp
            var providers = {!! $providervar !!};

            $(document).on('change', '#provider', function(e) {
                e.preventDefault()

                var val = $(this).val()

                console.log(val)

                $.each(providers, (index, item) => {
                    if (val == item) {
                        $('#config_' + item).addClass('d-block')
                        $('#config_' + item).removeClass('d-none')

                        $(item).find("input").each((idx, itm) => {
                            $(itm).prop('required', true);
                        })
                    } else {

                        $(item).find("input").each((idx, itm) => {
                            $(itm).prop('required', false);
                        })

                        $('#config_' + item).removeClass('d-block')
                        $('#config_' + item).addClass('d-none')
                    }

                })
            })
        });
    </script>
@endif
