<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', "Vik' App"))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
<div class="min-h-screen">
    {{-- On utilise ton nouveau header sans dark mode --}}
    @include('partials.header')

    @hasSection('header')
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>
    @endif

    <main>
        {{-- Hero section affichée au début du formulaire --}}
        @include('partials.hero-section', [
            'pageTitle' => 'VOTRE CLUB',
            'pageSubTitle' => 'gérer votre club au sein de ce hub',
            'imageUrl' => asset('img/heroSection/header.jpg')
        ])
        @yield('content')


    </main>
    @include('partials.footer')
</div>
</body>
</html>
