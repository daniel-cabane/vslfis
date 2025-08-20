<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'VS LFIS') }}</title>
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div style="height:100vh;display:flex;justify-content:center;align-items:center">
            Account unauthorized.
        </div>
    </body>
</html>

