<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }} | {{ env('APP_NAME') }}</title>

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="{{ asset('fronttheme/lib/animate/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('fronttheme/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{ asset('fronttheme/css/style.css') }}" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>

        @livewire('sections.topbar')
        @livewire('sections.navbar')

        {{ $slot }}

        @livewire('sections.footer')

        <!-- Back to Top -->
        <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('fronttheme/lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('fronttheme/lib/owlcarousel/owl.carousel.min.js') }}"></script>

        <!-- Contact Javascript File -->
        <script src="{{ asset('fronttheme/mail/jqBootstrapValidation.min.js') }}"></script>
        <script src="{{ asset('fronttheme/mail/contact.js') }}"></script>

        <!-- Template Javascript -->
        <script src="{{ asset('fronttheme/js/main.js') }}"></script>

        <!-- SweetAlert & Livewire Scripts -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <x-livewire-alert::scripts />
        @livewireScripts

        <!-- Livewire Debugging -->

    </body>
</html>
