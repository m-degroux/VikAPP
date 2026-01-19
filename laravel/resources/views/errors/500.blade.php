@extends('layouts.guest')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-gray-800">500</h1>
        <p class="text-2xl font-semibold text-gray-600 mt-4">Erreur serveur</p>
        <p class="text-gray-500 mt-2">Une erreur interne s'est produite. Veuillez réessayer plus tard.</p>
        
        <div class="mt-8">
            <a href="{{ route('welcome') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
