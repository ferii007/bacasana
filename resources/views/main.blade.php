<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>@yield('title', 'BacaSana')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @vite('resources/css/app.css')

        @yield('additionalStyles')
    </head>

    <body class="h-full" style="background-image: radial-gradient(circle, #273040, #212a3a, #1c2433, #161e2d, #111827);">
        <x-header.navbar />

        @yield('mainContent')

        {{-- @include('footer') --}}

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        
        @yield('additionalScripts')
    </body>
</html>