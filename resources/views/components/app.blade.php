@props(['title' => 'Food Fusion'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-background">
    <x-navbar />
    {{ $slot }}
    <x-auth.login-modal />
    <x-auth.register-modal />
</body>

</html>
