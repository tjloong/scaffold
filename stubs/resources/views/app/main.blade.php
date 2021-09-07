<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
	<link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="font-sans leading-tight text-gray-800 antialiased">
    @inertia
</body>
</html>