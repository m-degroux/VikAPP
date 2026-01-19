@extends('layouts.structure')

@section('title', 'Profil')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
                <p class="mt-2 text-gray-600">Gérez vos informations personnelles et vos paramètres de compte</p>
            </div>
            
            @include('pages.profile.edit')
        </div>
    </div>
@endsection