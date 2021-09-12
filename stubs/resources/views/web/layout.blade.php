<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @section('metatags')
        <x-scaffold::metatags/>
    @show

    <link rel="shortcut icon" href="{{ asset('storage/img/logo.svg') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">

    @if (app()->isLocale('zh-my'))
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@100;300;400;500;700;900&display=swap">
    @else
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap">
    @endif

    <style>
        html {
            background-color: white;
            line-height: 1.5;
        }
    </style>

    @stack('styles')

    <x-scaffold::gtm/>

    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
    <script src="{{ mix('js/web.js') }}" defer></script>
</head>
<body>
    <x-scaffold::gtm noscript/>
    
    <div id="root" class="min-h-screen bg-gray-100">
        @section('header')
            @include('web.partials.header')
        @show

        @yield('content')

        @section('footer')
            @include('web.partials.footer')
        @show
    </div>
</body>
</html>
