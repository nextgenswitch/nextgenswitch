@extends('layouts.app')

@section('title', 'Test')

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12 py-3">
            <button class="btn btn-primary" id="popup">Open Popup</button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dialer_modal">
                Show Dialer
            </button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#calling_modal">
                Ringing
            </button>

            <button data-toggle="modal" data-target="#sip_login_modal" class="btn btn-success">Login</button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#custom_form_modal">
                Custom Form
            </button>

            <a href="#" data-toggle="popover" title="Popover Header"
                data-content="Some content inside the popover">Toggle popover</a>

        </div>

        <div id="form-container"></div>
    </div>



    @include('test.dialpad')

    @include('test.custom_form')
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $("#popup").click(function() {
                openCenteredWindow('/test/popup', 'Dialer', 80, 70);
            });

            function openCenteredWindow(url, title, widthPercent, heightPercent) {

                const screenWidth = window.screen.width;
                const screenHeight = window.screen.height;


                const width = Math.round(screenWidth * (widthPercent / 100));
                const height = Math.round(screenHeight * (heightPercent / 100));


                const left = Math.round((screenWidth / 2) - (width / 2));
                const top = Math.round((screenHeight / 2) - (height / 2));


                const options =
                    `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`;


                window.open(url, title, options);
            }

        });
    </script>
@endpush
