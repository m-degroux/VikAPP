<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Administration</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation Admin -->
        <nav class="bg-red-800 border-b border-red-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex items-center">
                            <span class="text-white text-xl font-bold">ADMIN</span>
                        </div>
                        
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-white hover:text-gray-200">
                                Dashboard
                            </a>
                            <a href="{{ route('manage.raids.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-white hover:text-gray-200">
                                Raids
                            </a>
                            <a href="{{ route('manage.races.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-white hover:text-gray-200">
                                Courses
                            </a>
                            <a href="{{ route('manage.clubs.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-white hover:text-gray-200">
                                Clubs
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-white hover:text-gray-200 text-sm font-medium">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
