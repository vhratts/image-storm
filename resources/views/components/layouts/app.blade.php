<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Image-Storm | API Image editor</title>
        <link rel="icon" type="image/png" href="{{asset("/app.png")}}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link href="{{asset('default-style.css')}}" rel="stylesheet" />
        @endif
        <wireui:scripts />
        <script src="//unpkg.com/alpinejs" defer></script>
        <script defer src="https://umami-liart-ten.vercel.app/script.js" data-website-id="9d99d634-032c-4709-a898-c7d1f7df89de"></script>
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50 app-body">
        <div class="app-content">
            {{ $slot }}
        </div>
    </body>
</html>
