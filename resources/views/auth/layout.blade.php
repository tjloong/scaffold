<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-scaffold-component::metatags/>
    
    <link rel="shortcut icon" href="/storage/img/{{ config('scaffold.view.logo') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900">
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
    
    <x-scaffold-component::gtm/>
</head>
<body>
    <x-scaffold-component::gtm noscript/>

    <div id="root" class="min-h-screen">
        @yield('content')
    </div>

    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
    <script src="{{ mix('/js/auth.js') }}" defer></script>
</body>
</html>
