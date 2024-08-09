<!DOCTYPE html>
<html lang="en">
  <head>
    
    
    <title>@yield('title', 'EasyPBX')</title>
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
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
    
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    @stack('css')

  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->

    @include('agents.partials.header')

    
    <!-- Sidebar menu-->
  
    @include('agents.partials.sidebar')

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

      <p style="position: fixed; right: 1%; bottom: 0%">Design and developed by <a target="_blank" href="https://infosoftbd.com/"> <b>Infosoftbd Solutions</b></a></p>
    </main>




    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
   
    <!-- Latest compiled and minified JavaScript -->
  
    <script src="{{ asset('js/plugins/bootstrap-toaster.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.resizableColumns.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
        
    <script src="{{ asset('js/plugins/printThis.js') }}"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/fuse.js/3.2.0/fuse.min.js"></script>
    <!-- feather icon -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <script src="{{ asset('js/main.js'  ) }}"></script>
    <script src="{{ asset('js/plugins/crud.js') }}"></script>
    
    <!-- Page specific javascripts-->
    @stack('script')
  </body>
</html>