@extends('layouts.structure')

@section('title', 'VIKAPP - Gestion du Club')

@section('content')

    {{-- 1. En-tête de page (Hero) --}}
    @include('partials.hero-section', [
        'pageTitle' => 'Gestion du Club',
        'pageSubTitle' => 'Administrez les informations de votre structure',
        'imageUrl' => asset('img/raid_thumbnail.png') 
    ])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="h-8"></div>

        {{-- 2. Grille principale : Responsable & Formulaire Club --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

            {{-- Colonne Gauche : Informations du responsable (Lecture seule) --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-6 mx-auto w-3/4">
                    Responsable
                </h3>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 flex-grow">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                Nom complet
                            </label>
                            <div class="flex items-center bg-gray-50 px-4 py-3 rounded-lg border border-gray-200 text-gray-800 font-medium">
                                <i class="fas fa-user text-brand-green mr-3"></i>
                                {{ mb_strtoupper(mb_substr($manager->mem_firstname, 0, 1)) . mb_substr($manager->mem_firstname, 1) }}
                                {{ mb_strtoupper($manager->mem_name) }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                Téléphone
                            </label>
                            <div class="flex items-center bg-gray-50 px-4 py-3 rounded-lg border border-gray-200 text-gray-800 font-medium">
                                <i class="fas fa-phone text-brand-green mr-3"></i>
                                {{ $manager->mem_phone ?? 'Non renseigné' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                Email
                            </label>
                            <div class="flex items-center bg-gray-50 px-4 py-3 rounded-lg border border-gray-200 text-gray-800 font-medium">
                                <i class="fas fa-envelope text-brand-green mr-3"></i>
                                {{ $manager->mem_email }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonne Droite : Informations du club (Formulaire d'édition) --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-6 mx-auto w-3/4">
                    Informations du Club
                </h3>

                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 flex-grow">
                    <form method="post" action="{{ route('manage.clubs.update', ['club' => $club->club_id]) }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        {{-- Utilisation des styles de champs standards si possible, sinon adaptation manuelle --}}
                        <div>
                            <label for="club_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du club</label>
                            <input type="text" name="club_name" id="club_name" value="{{ old('club_name', $club->club_name) }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-3 px-4 border">
                            @error('club_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="club_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse du club</label>
                            <input type="text" name="club_address" id="club_address" value="{{ old('club_address', $club->club_address) }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-3 px-4 border">
                            @error('club_address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 flex items-center justify-between">
                            <button type="submit" class="btn-primary px-6 py-2 text-base font-bold rounded-lg shadow-md bg-brand-green text-white hover:bg-green-700 transition duration-300 w-full md:w-auto">
                                Sauvegarder les modifications
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition 
                                   x-init="setTimeout(() => show = false, 3000)"
                                   class="text-sm text-green-600 font-bold flex items-center">
                                    <i class="fas fa-check mr-2"></i> Sauvegardé !
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    <section class="max-w-7xl mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-sm font-sans my-20">
        <div class="flex flex-col md:flex-row gap-4 mt-5">
            <div class="flex-1 bg-gray-50 border border-gray-100 rounded-md py-8 flex flex-col items-center shadow-sm">
                <span class="text-4xl font-black text-green-700">{{ $nbRaids }}</span>
                <span class="text-xs font-semibold text-gray-500 tracking-widest mt-1 uppercase">Raids organisés</span>
            </div>

            <div class="flex-1 bg-gray-50 border border-gray-100 rounded-md py-8 flex flex-col items-center shadow-sm">
                <span class="text-4xl font-black text-green-700">{{ $nbRaces }}</span>
                <span class="text-xs font-semibold text-gray-500 tracking-widest mt-1 uppercase">Courses organisées</span>
            </div>
        </div>
    </section>

    <section class="mt-8 border-t pt-6">
        <h2 class="text-2xl font-bold mb-4">Adhérents du club</h2>

        @if($members && $members->count() > 0)
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Nom</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Prénom</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ strtoupper($member->mem_name) }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $member->mem_firstname }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $member->mem_email ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="badge badge-success">Adhérent</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 italic">Aucun adhérent pour le moment.</p>
        @endif
    </section>
@endsection
