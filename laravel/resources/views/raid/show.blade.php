@extends('layouts.structure')

@section('title', 'VIKAPP - Detail du raid')

@section('content')

    {{-- Hero Section: Reusable header component for visual consistency --}}
    @include('partials.herosection', [
        'pageTitle' => 'Détail du raid',
        'pageSubTitle' => 'Découvrez les raids à venir près de chez vous',
        'imageUrl' => asset('img/raid_thumbnail.png')
    ])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="h-8"></div>

        {{-- Main information grid (3 columns on large screens) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">

            {{-- 1. Raid Presentation --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-4 mx-auto w-3/4">
                    1. Présentation
                </h3>
                <ul class="flex-grow space-y-3 list-disc list-inside text-gray-700 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <li><strong class="text-brand-dark">Nom du raid</strong> : {{$raid->raid_name}}</li>
                    <li><strong class="text-brand-dark">Lieu du raid</strong> : {{$raid->raid_place}}</li>
                </ul>
            </div>

            {{-- 2. Dates and Schedule --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-4 mx-auto w-3/4">
                    2. Calendrier
                </h3>
                <ul class="flex-grow space-y-3 list-disc list-inside text-gray-700 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <li><strong class="text-brand-dark">Dates du raid</strong> :
                        <br><span class="ml-5">Du {{ \Carbon\Carbon::parse($raid->raid_start_date)->format('d/m/Y') }}</span>
                        <br><span class="ml-5">Au {{ \Carbon\Carbon::parse($raid->raid_end_date)->format('d/m/Y') }}</span>
                    </li>
                    <li><strong class="text-brand-dark">Période d'inscription</strong> :
                        <br><span class="ml-5">Du {{ \Carbon\Carbon::parse($raid->raid_reg_start_date)->format('d/m/Y') }}</span>
                        <br><span class="ml-5">Au {{ \Carbon\Carbon::parse($raid->raid_reg_end_date)->format('d/m/Y') }}</span>
                    </li>
                </ul>
            </div>

            {{-- 3. Organization and Contact --}}
            <div class="flex flex-col h-full">
                <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-4 mx-auto w-3/4">
                    3. Organisation
                </h3>
                <ul class="flex-grow space-y-3 list-disc list-inside text-gray-700 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <li><strong class="text-brand-dark">Contact direct</strong> : {{$raid->raid_contact}}</li>
                    <li><strong class="text-brand-dark">Site web</strong> : 
                        <a href="{{ $raid->raid_website }}" target="_blank" class="text-blue-600 hover:underline">
                            {{$raid->raid_website}}
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- 4. Raid Races List (Full width) --}}
        <div class="mb-12">
            <h3 class="text-center text-white bg-brand-green py-3 px-6 rounded-full text-lg font-bold shadow-md mb-8 mx-auto w-full md:w-1/2">
                4. Les courses du Raid
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($raid->races as $race)
                    @php
                        // Calculate remaining team slots (Safety logic: ensuring value isn't negative)
                        $teamsLeft = max(0, $race->race_max_team - $race->teams->count());
                        
                        // Maximum team capacity
                        $teamMax = $race->race_max_team;

                        // Retrieve and format age limits from relationship
                        $ageLimits = $race->ageCategories->map(function($ageCat){
                            return $ageCat->age_min . '-' . $ageCat->age_max . ' ans';
                        })->implode(', ');

                        /** * TIME FORMATTING LOGIC
                         * Splitting HH:MM:SS string to avoid Carbon's >24h overflow issues 
                         */
                        $timeParts = explode(':', $race->race_duration);
                        $displayTime = ($timeParts[0] ?? '00') . 'h' . ($timeParts[1] ?? '00');
                    @endphp

                    {{-- Event Card: Featuring shadows, badges, and icons for UI/UX --}}
                    <div class="card-event flex flex-col relative bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden"> 
    
                        {{-- Availability Badge --}}
                        <div class="absolute top-2 left-2 bg-green-700 text-white text-[12px] px-2 py-1 rounded shadow z-10 font-semibold">
                            Places : {{ $teamsLeft }} / {{ $teamMax }}
                        </div>

                        <img src="{{ asset('img/raid_thumbnail.png') }}"
                                class="w-full h-40 object-cover">

                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-lg mb-2 text-brand-dark">
                                {{ $race->race_name }}
                            </h3>

                            <div class="text-sm text-gray-600 space-y-1 mb-4 flex-grow">
                                <p><i class="fas fa-map-marker-alt w-5 text-center"></i> {{ $raid->raid_place }}</p>
                                <p><i class="far fa-calendar-alt w-5 text-center"></i> {{ \Carbon\Carbon::parse($race->race_start_date)->format('d/m/Y H:i') }}</p>
                                {{-- Display formatted duration --}}
                                <p><i class="far fa-clock w-5 text-center"></i> Durée : {{ $displayTime }}</p>
                                @if($ageLimits)
                                    <p><i class="fas fa-users w-5 text-center"></i> Ages : {{ $ageLimits }}</p>
                                @endif
                            </div>

                            {{-- Navigation to specific race details --}}
                            <a href="{{ route('race.info', $race->race_id) }}"
                                class="btn-primary w-full text-center py-2 mt-auto rounded hover:bg-opacity-90 transition">
                                Voir le détail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection