@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Tableau de bord coureur</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Courses terminées</div>
            <div class="text-3xl font-bold text-gray-900 mt-2">{{ $completedRaces ?? 0 }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Raids participés</div>
            <div class="text-3xl font-bold text-gray-900 mt-2">{{ $participatedRaids ?? 0 }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Prochaines courses</div>
            <div class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingRaces ?? 0 }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Mes dernières courses</h2>
            <!-- Liste des courses -->
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Statistiques</h2>
            <a href="{{ route('runner.stats') }}" class="text-indigo-600 hover:underline">
                Voir toutes les statistiques
            </a>
        </div>
    </div>
</div>
@endsection
