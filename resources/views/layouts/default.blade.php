<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Base CSS (global for all pages) -->
    <link rel="stylesheet" href="{{ asset('css/utils/base.css') }}">

    <!-- Page-specific CSS -->
    @yield('page-css')

    <title>@yield('title', 'ReuniFind')</title>
  </head>
  <body>
    
    <!-- HEADER SECTION -->
    <header>
      @yield('header')
    </header>
    
    <!-- CONTENT SECTION -->
    <main>
      @yield('content')
    </main>

    <!-- FOOTER SECTION -->
    <footer>
      @yield('footer')
    </footer>

    <!-- Page-specific JS -->
    @yield('page-js')
  </body>
</html>