<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    

      <!-- bs CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bs.css') }}">

    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('js/font-awesome/css/font-awesome.min.css') }}">
    <title>@yield('title')</title>
    
    @stack('css')

  </head>
  <body>
    @yield('content')
    

    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- feather icon -->
    
    <script src="{{ asset('js/feather.min.js') }}"></script>

    <script src="{{ asset('js/main.js') }}"></script>
    <!-- The javascript plugin to display page loading on top
    <script src="{{ asset('js/plugins/pace.min.js') }}"></script>-->
    
    @stack('js')

    <script type="text/javascript">
      // Login Page Flipbox control
      $('.login-content [data-toggle="flip"]').click(function() {
      	$('.login-box').toggleClass('flipped');
      	return false;
      });
    </script>
  </body>
</html>