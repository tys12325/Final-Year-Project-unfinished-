<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'My Website')</title>
        @yield('head')
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/userDashboard.css'])
        @vite(['resources/css/HeaderFooter.css'])
        @vite(['resources/css/BackButton.css'])
        @vite(['resources/js/UserDashboard.js'])




    </head>
    <body>

        @include('partials.header')  {{-- Include Header --}}

        <main class="content">
            @yield('content')  {{-- Dynamic Content --}}
            @yield('scripts')

        </main>

     
    </body>
</html>
