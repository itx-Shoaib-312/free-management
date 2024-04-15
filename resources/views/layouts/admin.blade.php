<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | {{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    {{ $plugins_css ?? '' }}
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">

    <!-- Global Style Overwrite -->
    <style>
        .swal2-title.centered {
            grid-row: 1/99;
            align-self: center;
        }
    </style>
    {{ $styles ?? '' }}
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        @include('layouts.partials.admin.header')

        @include('layouts.partials.admin.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            {{ $slot }}
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.partials.admin.footer')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    {{ $plugins_js ?? '' }}
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

    <!-- Plugin Configurations -->
    <script>
        const swalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            customClass: {
                title: 'centered',
            },
            timer: 2500,
        });
    </script>

    @if (session()->has('message'))
        <!-- Custom Script -->
        <script>
            const toastData = @json(session()->get('message', []));
            $(document).Toasts('create', {
                title: toastData.type.toUpperCase(),
                body: toastData.content,
                autohide: true,
                delay: 2500,
                class: `bg-${toastData.type}`,
            });
        </script>
    @endif

    {{ $scripts ?? '' }}
</body>

</html>
