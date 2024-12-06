{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html> --}}


<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'MFI-FR Admin') }}</title>
    <link rel="stylesheet" href="{{ asset('adminassets/bootstrap.min.css') }}">
    <link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminassets/style.css') }}">
    @yield('styles')
</head>

<body>

    <div class="container">
        @include('admin.navbar')

        @yield('content')
    </div>

    <script src="{{ asset('adminassets/jquery.min.js') }}"></script>
    <script src="{{ asset('adminassets/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adminassets/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/popper.min.js') }}"></script>
    {{-- <script src="assets/dataTables.bootstrap4.min.js"></script> --}}

    <script src="{{ asset('assets/js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/select2/select2.active.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-select/bootstrapSelect.active.js') }}"></script>
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr/toastr.active.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/datatables.active.js') }}"></script>
    {{-- <script src="assets/js/script.js"></script> --}}
    @yield('scripts')
</body>

</html>
