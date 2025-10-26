@props(['title' => 'Food Fusion'])
@vite('resources/js/app.js')
@vite('resources/css/app.css')

<!DOCTYPE html>
<html lang="en" x-data>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }}</title>
    </head>

    <body class="bg-background">
        <x-navbar />
        <main {{ $attributes->class(['flex w-full px-20 sm:px-10 py-7 gap-5']) }}>
            @yield('content')
        </main>
        <x-auth.login-modal />
        <x-auth.register-modal />

        <x-footer />
    </body>

</html>
