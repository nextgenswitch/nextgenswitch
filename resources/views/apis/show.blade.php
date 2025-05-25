@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading d-flex justify-content-between">
        
            <h4 class="mb-5">{{ __("API key and secret") }}</h4>
        

        <div>
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['apis.api.destroy', $api->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('apis.api.index') }}" class="btn btn-primary" title="{{ __('Show All Api') }}">
                        <span class="fa fa-list" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="fa fa-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Api',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Api.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        @include('partials.message')
        <table class="table table-borderless">
            <p>{{ __('API key and secret generated successfully. Please ensure to save the secret key securely. Once you leave this page, the API secret will not be visible.') }}</p>
            <tr>
                <td width="5%">Key</td>
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $api->key }}" id="input-api-key">
                        <div class="input-group-append">
                            <span class="btn btn-primary" id="copy-key"><i class="fa fa-copy"></i></span>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>Secret</td>
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control" id="input-api-secret" value="{{ $api->secret }}">
                        <div class="input-group-append">
                            <span class="btn btn-primary" id="copy-secret"><i class="fa fa-copy"></i></span>
                        </div>
                </div>
                </td>
            </tr>
        </table>

       

    </div>
</div>

@endsection


@push('script')
    
    <script>
        $(document).ready(function() {

            $("#copy-secret").click(function() {
                $("#input-api-secret").select();
                document.execCommand("copy");
            })

            $("#copy-key").click(function() {
                $("#input-api-key").select();
                document.execCommand("copy");
            })


        })
    </script>
@endpush