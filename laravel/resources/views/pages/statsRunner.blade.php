{{-- Page: Runner Dashboard --}}
@extends('layouts.structure')

@section('title', 'Mon espace coureur')

@section('content')

    {{-- Hero Section: Reusable header with background image and dynamic titles --}}
    @include('partials.herosection', [
        'pageTitle' => 'Mon espace coureur',
        'pageSubTitle' => 'Toutes vos balises dans votre poche.',
        'imageUrl' => asset('img/heroSection/header.jpg'),
    ])

    <section class="max-w-4xl mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-sm font-sans my-20">
        <div>
            <p class="title-section" style="color:green;">Mon Tableau de bord</p>
            <div class="h-0.5 w-full bg-green-700 mt-2"></div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mt-5">
            {{-- Total Races Counter --}}
            <div class="flex-1 bg-gray-50 border border-gray-100 rounded-md py-8 flex flex-col items-center shadow-sm">
                <span class="text-4xl font-black text-green-700">{{ $nbCourses }}</span>
                <span class="text-xs font-semibold text-gray-500 tracking-widest mt-1 uppercase">Courses</span>
            </div>

            {{-- Cumulative Points Counter --}}
            <div class="flex-1 bg-gray-50 border border-gray-100 rounded-md py-8 flex flex-col items-center shadow-sm">
                <span class="text-4xl font-black text-green-700">{{ $totalPoints }}</span>
                <span class="text-xs font-semibold text-gray-500 tracking-widest mt-1 uppercase">Points cumulés</span>
            </div>

            {{-- Total Podiums Counter --}}
            <div class="flex-1 bg-gray-50 border border-gray-100 rounded-md py-8 flex flex-col items-center shadow-sm">
                <span class="text-4xl font-black text-green-700">{{ $nbPodiums }}</span>
                <span class="text-xs font-semibold text-gray-500 tracking-widest mt-1 uppercase">Podiums</span>
            </div>
        </div>
    </section>

    <section class="max-w-4xl mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-sm font-sans my-20">
        <div>
            <p class="title-section" style="color:green;">Historique des courses</p>
            <div class="h-0.5 w-full bg-green-700 mt-2"></div>
            <p class="text-gray-400 leading-relaxed text-sm mb-6 mt-3">Cliquez sur votre classement pour voir le tableau complet de la course.</p>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>COURSE / ÉQUIPE</th>
                    <th class="text-center">CLASSEMENT GLOBAL</th>
                    <th class="text-center">TEMPS ÉQUIPE</th>
                    <th class="text-right">POINTS</th>
                    <th class="py-3 font-semibold text-right">STATUT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $race)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4">
                            {{-- Displays Team Name and Race Name stored in SQL --}}
                            <div class="font-bold text-gray-800">{{ $race->team_name }}</div>
                            <div class="text-xs text-gray-400 font-normal">{{ $race->race_name }}</div>
                        </td>
                        {{-- Global Ranking with Modal Trigger to see full results --}}
                        <td class="text-center cursor-pointer"
                            onclick="openModal({{ $race->race_id }}, '{{ $race->raid_name }}')">
                            <span
                                class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full hover:bg-green-200 transition-colors">
                                {{ $race->rank }}{{ $race->rank == 1 ? 'er' : 'ème' }} /
                                {{ $race->total_participants }}
                            </span>
                        </td>
                        <td class="text-center text-gray-600 font-medium">{{ $race->team_time }}</td>
                        <td class="text-right font-bold text-gray-800">{{ $race->team_point }} pts</td>
                        <td class="py-4 text-right">
                            <div class="text-right font-bold text-gray-800 flex justify-end items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $race->statut ?: 'Aucun' }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Include results modal view --}}
        @include("pages.results")
        
    </section>

    <section class="max-w-4xl mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-sm font-sans my-10">
        <div>
            <p class="title-section" style="color:green;">Vos prochaines courses</p>
            <div class="h-0.5 w-full bg-green-700 mt-2"></div>
            <p class="text-gray-400 leading-relaxed text-sm mb-6 mt-3">Voici les épreuves sur lesquelles vous êtes inscrit.</p>
        </div>

        @if ($upcomingRaces->isEmpty())
            <div class="text-center py-8 text-gray-500 italic">
                Aucune course prévue pour le moment.
            </div>
        @else
            <table class="admin-table w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs uppercase text-gray-500 border-b border-gray-100">
                        <th class="py-3 font-semibold">DOSSARD</th>
                        <th class="py-3 font-semibold">COURSE / DATE</th>
                        <th class="py-3 font-semibold">DISTANCE</th>
                        <th class="py-3 font-semibold text-right">ÉQUIPE & STATUT</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($upcomingRaces as $race)
                        <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                            {{-- Team ID acts as the Bib Number (Dossard) --}}
                            <td class="py-4 font-bold text-gray-800 text-lg">
                                #{{ $race->team_id }}
                            </td>

                            <td class="py-4">
                                <div class="font-bold text-gray-800">{{ $race->raid_name }}</div>
                                <div class="text-green-700 font-medium text-xs uppercase mb-1">{{ $race->race_name }}</div>
                                <div class="flex items-center text-gray-500 text-xs mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{-- Uses Carbon for date formatting --}}
                                    {{ \Carbon\Carbon::parse($race->race_start_date)->format('d/m/Y à H:i') }}
                                </div>
                            </td>

                            <td class="py-4">
                                <span class="bg-gray-100 text-gray-700 py-1 px-2 rounded text-xs font-bold border border-gray-200">
                                    {{ floatval($race->race_length) }} km
                                </span>
                            </td>

                            <td class="py-4 text-right">
                                <div class="font-bold text-blue-900 mb-1">{{ $race->team_name }}</div>
                                <div class="text-xs text-gray-500 flex justify-end items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $race->statut ?: 'Aucun' }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

@endsection