<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- <style>
            @import url('https://fonts.googleapis.com/css2?family=Shrikhand&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Sue+Ellen+Francisco&display=swap');
            .pimTitleFont {
                font-family: "Shrikhand", serif;
                font-weight: 400;
                font-style: normal;
            }
            .pimSubtitleFont {
                font-family: "Sue Ellen Francisco", cursive;
                font-weight: 400;
                font-style: normal;
            }
        </style> -->

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VS LFIS') }}</title>
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            <v-app>
                <v-container fluid pa-0>
                    <main-toolbar :user="{{ json_encode(Auth::user()) }}"></main-toolbar>
                    <v-main>
                        <router-view></router-view>
                    </v-main>
                    <notification-center></notification-center>
                    <loading-indicator style='position:fixed;bottom:0px;z-index:1000'></loading-indicator>
                    <!-- <div style="position:fixed;top:75px;left:12.5vw;z-index:9999">
                    </div> -->
                </v-container>
            </v-app>
        </div>
        <script>
            window.Laravel = { env: '{{ app()->environment() }}' };
        </script>
    </body>
</html>

