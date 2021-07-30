<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>@yield('title', 'Spotify Playground')</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    </head>
    <body>
        <div class="grid-container fluid">
            @yield('content')
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
        <script>$(document).foundation();</script>
    </body>
</html>
