@extends('layouts.structure')

@section('title', 'VIKAPP - Détail de la course')

@section('content')

    {{-- Hero Section: Reusable component for page headers --}}
    @include('partials.herosection', [
        'pageTitle' => 'Détail de la course',
        'pageSubTitle' => 'Découvrez les courses à venir',
        'imageUrl' => asset('img/raid_thumbnail.png')
    ])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="h-8"></div>

        {{-- Main Grid: 2 columns on desktop (lg), 1 column on mobile --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">

            {{-- 1. General Information Section --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-4 mx-auto w-3/4">
                    1. Informations Générales
                </h3>
                <ul class="flex-grow space-y-3 list-disc list-inside text-gray-700 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <li><strong class="text-brand-dark">Nom de la course</strong> : {{$race->race_name}}</li>
                    <li><strong class="text-brand-dark">Type de course</strong> : {{$race->type->type_name ?? 'Non défini'}}</li>
                    <li><strong class="text-brand-dark">Difficulté</strong> : {{$race->difficulty->dif_name ?? 'Non défini'}}</li>
                    
                    @php
                        // TIME FORMATTING: Handle durations that might exceed 24 hours
                        // Format the HH:MM:SS string to a more readable 'XXhYY' format
                        $timeParts = explode(':', $race->race_duration);
                        $displayDuration = ($timeParts[0] ?? '00') . 'h' . ($timeParts[1] ?? '00');
                    @endphp
                    <li><strong class="text-brand-dark">Durée estimée</strong> : {{ $displayDuration }}</li>
                </ul>
            </div>

            {{-- 2. Schedule and Venue Section --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-4 mx-auto w-3/4">
                    2. Programmation et Lieu
                </h3>
                <ul class="flex-grow space-y-3 list-disc list-inside text-gray-700 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <li><strong class="text-brand-dark">Date et Heure</strong> :
                        {{ \Carbon\Carbon::parse($race->race_start_date)->format('d/m/Y H:i') }}
                    </li>
                    <li><strong class="text-brand-dark">Fin estimée</strong> :
                        {{ \Carbon\Carbon::parse($race->race_end_date)->format('d/m/Y \à H:i') }}
                    </li>
                    <li><strong class="text-brand-dark">Lieu</strong> : {{$race->raid->raid_place ?? 'Non précisé'}}</li>
                </ul>
            </div>

            {{-- 3. Pricing and Age Categories Section --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-4 mx-auto w-3/4">
                    3. Tarifs et Catégories
                </h3>
                <ul class="flex-grow space-y-3 list-disc list-inside text-gray-700 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <li><strong class="text-brand-dark">Prix par catégorie</strong> :
                        {{-- Loop through age categories linked via pivot table to get specific prices --}}
                        @foreach($race->ageCategories as $age)
                            <div class="ml-5 text-sm text-gray-600">
                                • {{ $age->age_min }} - {{ $age->age_max }} ans : {{ $age->pivot->bel_price ?? 'Non défini' }} €
                            </div>
                        @endforeach
                    </li>
                    <li><strong class="text-brand-dark">Réduction licenciés</strong> : {{ $race->race_licence_discount ?? 'Aucune' }}</li>
                    <li><strong class="text-brand-dark">Repas</strong> : 
                        {{ $race->race_meal_price ?? 'Non disponible' }} €
                    </li>
                    <li><strong class="text-brand-dark">Critères d’âge</strong> : 
                        Conditions d’âge pour équipes.
                    </li>
                </ul>
            </div>

            {{-- 4. Participation Constraints Section --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-4 mx-auto w-3/4">
                    4. Contraintes
                </h3>
                <ul class="flex-grow space-y-3 list-disc list-inside text-gray-700 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <li><strong class="text-brand-dark">Participants (Min/Max)</strong> : {{$race->race_min_part ?? '-'}} / {{$race->race_max_part ?? '-'}}</li>
                    <li><strong class="text-brand-dark">Équipes (Min/Max)</strong> : {{$race->race_min_team ?? '-'}} / {{$race->race_max_team ?? '-'}}</li>
                    <li><strong class="text-brand-dark">Clôture des inscriptions</strong> : 
                        <span>{{ \Carbon\Carbon::parse($race->raid->raid_reg_end_date)->format('d/m/Y') }}</span>
                    </li>
                </ul>
            </div>

            {{-- 5. Contact and Organization Section (Spans 2 columns on desktop for centering) --}}
            <div class="flex flex-col h-full lg:col-span-2 lg:w-1/2 lg:mx-auto">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-4 mx-auto w-3/4">
                    5. Contact et Organisation
                </h3>
                <ul class="flex-grow space-y-3 list-disc list-inside text-gray-700 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <li><strong class="text-brand-dark">Club</strong> : {{$race->raid->raid_club ?? 'Non précisé'}}</li>
                    <li><strong class="text-brand-dark">Responsable</strong> : {{$race->raid->raid_contact ?? 'Non précisé'}}</li>
                    <li><strong class="text-brand-dark">Email</strong> : {{$race->raid->raid_contact_email ?? 'Non précisé'}}</li>
                    <li><strong class="text-brand-dark">Site Web</strong> : <a href="{{$race->raid->raid_website}}" class="text-blue-600 hover:underline" target="_blank">{{$race->raid->raid_website ?? 'Lien'}}</a></li>
                </ul>
            </div>

        </div> {{-- End of Main Grid --}}

        {{-- 6. Registration Call-to-Action (Centered below the grid) --}}
        <div class="text-center mt-12 pb-12">
            {{-- Check if the race date is still in the future to allow registration --}}
            @if(\Carbon\Carbon::parse($race->race_end_date)->isFuture())
                {{-- Only show registration link for logged-in users --}}
                @auth('web')
                    <div class="text-center">
                        <a href="{{route('create.team', ['run' => $race->race_id])}}" class="btn-primary px-8 py-4 text-xl shadow-lg">
                            S’inscrire à la course
                        </a>
                    </div>
                @endauth
            @else
                {{-- Case: Race is finished, show ranking modal trigger instead --}}
                <button onclick="openModal({{ $race->race_id }}, '{{ addslashes($race->race_name) }}')" class="btn-primary px-8 py-4 text-xl shadow-lg">
                    Voir le classement
                </button>
            @endif
        </div>
    </div>

    {{-- Results partial used for the ranking modal --}}
    @include('pages.results')
@endsection