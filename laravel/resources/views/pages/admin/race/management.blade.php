{{-- Page: Race Manager --}}
@extends('layouts.structure')
@section('title', 'Responsable des courses')
@section('content')

    {{-- Hero Section: Reusable component for the management dashboard header --}}
    @include('partials.hero-section', ['pageTitle' => 'Gestion des courses', 'pageSubTitle' => 'Administrez et supervisez vos événements de course avec efficacité.', 'imageUrl' => asset('img/heroSection/header.jpg')])
    
    <div class="admin-table-container">
        <h2 class="text-2xl font-bold text-green-700 mb-1">Choisissez la course à manager</h2>
        <hr class="border-t-2 border-green-600 w-full mb-4">

        {{-- Grid display: Responsive columns from 1 (mobile) to 3 (large screens) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @foreach($races as $race)
                @php
                    // Retrieve registration stats: current number of teams vs maximum capacity
                    $teamsCount = DB::table('vik_join_race')
                        ->where('race_id', $race->race_id)
                        ->count();

                    $teamsLeft = max(0, $race->race_max_team - $teamsCount);
                    $teamMax = $race->race_max_team;
                    
                    // Fetch associated raid info for location details
                    $raid = DB::table('vik_raid')->where('raid_id', $race->raid_id)->first();

                    /** * TIME DURATION PARSING CORRECTION:
                     * Manual string split is used to prevent Carbon::parse() from capping values at 24h.
                     * Database format is HH:MM:SS
                     */
                    $durationParts = explode(':', $race->race_duration);
                    $hours = $durationParts[0] ?? '00';
                    $minutes = $durationParts[1] ?? '00';
                    $displayDuration = $hours . 'h' . $minutes;
                @endphp

                <div class="card-event flex flex-col relative">
                    {{-- Capacity Badge --}}
                    <div class="absolute top-2 left-2 bg-green-700 text-white text-[12px] px-2 py-1 rounded shadow z-10">
                        <strong>Équipes restantes : {{ $teamsLeft }} / {{ $teamMax }}</strong>
                    </div>
                    
                    <img src="{{ asset('img/raid_thumbnail.png') }}" class="w-full h-32 object-cover mb-3 rounded">

                    <h3 class="font-bold text-md mb-1">
                        {{ $race->race_name }}
                    </h3>

                    {{-- Race metadata summary --}}
                    <div class="text-[11px] text-gray-500 space-y-0.5 mb-4">
                        <p><strong>Lieu :</strong> {{ $raid->raid_place ?? 'Non défini' }}</p>
                        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($race->race_start_date)->format('d/m/Y') }}</p>
                        {{-- Displaying corrected duration --}}
                        <p><strong>Temps max :</strong> {{ $displayDuration }}</p>
                    </div>
                    
                    {{-- Link to the specific management dashboard for this race --}}
                    <a href="{{ route('manage.races.show', ['race' => $race->race_id]) }}"
                       class="btn-primary w-full text-center">
                        Manager
                    </a>

                    {{-- Action buttons section --}}
                    <a href="{{ route('races.show', ['race' => $race->race_id]) }}"
                       class="btn-primary w-full text-center mt-2">
                        Détails
                    </a>
                </div>
            @endforeach

        </div>
    </div>
@endsection