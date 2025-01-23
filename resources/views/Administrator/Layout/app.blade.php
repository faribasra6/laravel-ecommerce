<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Your app description here">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" onerror="this.href='{{ asset('admin/css/simple-datatables-fallback.css') }}'" />
    <link href="{{ asset('admin/css/styles.css') }}?v={{ time() }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/customstyles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('links')
</head>

<body class="sb-nav-fixed">
    <div class="wrapper">
        @include('Administrator.layout.partials.navbar')
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                @include('Administrator.layout.partials.sidebar')
            </div>
            <div id="layoutSidenav_content" style="background-color: #faf1d4;">
              
                    <!-- Main Content Goes Here -->
                    <main >
                        @yield('content')
                    </main>
                    <!-- Main Content Ends Here -->
                @include('Administrator.layout.partials.footer')
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('admin/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('admin/js/datatables-simple-demo.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        </script>
    @yield('customScript')
</body>
</html>


    
    