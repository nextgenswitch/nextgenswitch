<!DOCTYPE html>
<html lang="en">
  <head>
@php
$title = env('APP_NAME', 'EasyPBX');
if(config('licence.brand_name')) $title = config('licence.brand_name')
@endphp

    <title>@yield('title', $title)</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bs.css') }}">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css')}}">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-toaster.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.resizableColumns.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-popover-x.min.css') }}">
    
    
    <!-- Font-icon css-->
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('js/font-awesome/css/font-awesome.min.css') }}"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css')}}">
    <style>
    .call-now:hover {cursor: pointer;}
  </style>
    @stack('css')

  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->

    @include('partials.header')

    
    <!-- Sidebar menu-->
  
    @include('partials.sidebar')

    <main class="app-content">
      
      <div class="row">
        <div class="col-md-12">
        <div class="tile">
        <div class="tile-body">  
          @yield('content')  
        </div>
      </div>
      </div>    
      </div>

      
    </main>

    @include('dialer.modal')
    <script type="text/javascript">
      window.media_play = "{!! route('voice_files.voice.play')  !!}";
    </script>

    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
   
    <!-- Latest compiled and minified JavaScript -->
  
    <script src="{{ asset('js/plugins/bootstrap-toaster.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.resizableColumns.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-popover-x.min.js') }}"></script>
    
        
    <script src="{{ asset('js/plugins/printThis.js') }}"></script>
     
    <!-- feather icon -->
    
    <script src="{{ asset('js/feather.min.js') }}"></script>


    <script src="{{ asset('js/main.js'  ) }}"></script>
    <script src="{{ asset('js/plugins/crud.js') }}"></script>
    
    <!-- Page specific javascripts-->
    @stack('script')
    <script>
      $(function() {
        var menu_group = '{{ config('menu.group','dashboard') }}';
        $('#' + menu_group).addClass('is-expanded');
      });
      
    </script>  

  </body>
</html>