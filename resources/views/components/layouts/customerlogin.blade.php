<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }} | {{ env('APP_NAME') }}</title>

    {{-- Theme style --}}
    <link href="{{ asset('fronttheme/css/style.css') }}" rel="stylesheet">

    {{-- Page-specific styles --}}
    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    {{ $slot }}

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>

</html>
