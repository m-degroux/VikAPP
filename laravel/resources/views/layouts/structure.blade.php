<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "Vik' App")</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @include('partials.header')

    <main>
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-green-600 text-white px-6 py-3 rounded shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @yield('content', 'Pas de contenu pour le moment')
        
        @if (session('success'))
            <script>
                setTimeout(() => {
                    const el = document.getElementById('flash-success');
                    if (el) {
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 500);
                    }
                }, 3000); // 3 secondes
            </script>
        @endif
    </main>

    @if (session('success'))
        <div id="flash-success"
            class="fixed top-5 left-1/2 -translate-x-1/2 z-50
               bg-green-600 text-white px-6 py-3 rounded shadow-lg
               transition-opacity duration-500">
            {{ session('success') }}
        </div>
    @endif

    @include('partials.footer')

</body>

</html>
