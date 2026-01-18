@extends('layouts.structure')

@section('title', 'VIKAPP - Raids')

@section('content')

    @include('partials.herosection', [
        'pageTitle' => 'Nos prochains raids',
        'pageSubTitle' => 'Découvrez les raids à venir près de chez vous',
        'imageUrl' => asset('img/raid_thumbnail.png')
    ])

    <div id="liste-raids"></div>

    <section class="max-w-6xl mx-auto px-6 py-12">
        <p class="subtitle italic">À venir</p>
        <h2 class="title-section mb-8">
            Prochains raids
        </h2>

        <form action="{{ route('raid.index') }}" method="GET" id="search-form"
              class="mb-10 bg-gray-50 p-6 rounded-lg shadow-sm">
            <div class="flex flex-col md:flex-row gap-6 items-end">

                <div class="flex-1 w-full">
                    <label for="city-input" class="block text-sm font-medium text-gray-700 mb-1">Ville ou
                        Département</label>
                    <input type="text" name="location" id="city-input" value="{{ request('location') }}"
                           placeholder="Ex: Caen..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 p-2 border">
                </div>

                <div class="w-full md:w-48">
                    <label for="radius" class="block text-sm font-medium text-gray-700 mb-1">
                        Distance : <span id="radius-value"
                                         class="font-bold text-green-700">{{ request('radius', 50) }}</span> km
                    </label>
                    <input type="range"
                           name="radius"
                           id="radius"
                           min="5"
                           max="200"
                           step="5"
                           value="{{ request('radius', 50) }}"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-700">
                    <div class="flex justify-between text-[10px] text-gray-500 px-1">
                        <span>5km</span>
                        <span>200km</span>
                    </div>
                </div>

                <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
                <input type="hidden" name="lon" id="lon" value="{{ request('lon') }}">

                <div class="w-full md:w-auto">
                    <button type="submit"
                            class="bg-green-700 text-white px-6 py-2 rounded-md hover:bg-green-800 font-bold w-full h-[42px]">
                        Filtrer
                    </button>
                </div>

                <a href="{{ route('raid.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 flex items-center justify-center h-[42px]">
                    Effacer
                </a>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">

            <!-- To do : Changer l'affichage des raids à venir -->
            <!-- J'ai besoin des 3 raids à venir, avec comme data : image, titre, date, lieu, âge minimum, lien vers la page du raid -->
            <!-- Fin de la gestion quand le controller et le model seront faits -->
            @foreach($raids as $raid)
                @php
                    $nextRace = $raid->nextRace();
                    $timeUntil = $raid->timeUntilNextRace();
                @endphp

                <div class="card-event flex flex-col relative">
                    @if($raid->isPast())
                        <div class="absolute top-2 left-2 bg-red-700 text-white text-[12px] px-2 py-1 rounded shadow z-10">
                            <strong>Raid terminé</strong>
                        </div>
                    @elseif(!$nextRace)
                        <div class="absolute top-2 left-2 bg-orange-600 text-white text-[12px] px-2 py-1 rounded shadow z-10">
                            <strong>Aucune course programmée</strong>
                        </div>
                    @elseif($timeUntil)
                        <div class="absolute top-2 left-2 bg-green-700 text-white text-[12px] px-2 py-1 rounded shadow z-10">
                            <strong>Prochaine course : {{ $timeUntil }}</strong>
                        </div>
                    @endif
                    <img src="{{ asset('img/raid_thumbnail.png') }}"
                        class="w-full h-32 object-cover mb-3 rounded">
                    <h3 class="font-bold text-md mb-1">{{ $raid->raid_name }}</h3>
                    <div class="text-[11px] text-gray-500 space-y-0.5 mb-4">
                        @if($nextRace)
                            <p>
                                Date :
                                {{ \Carbon\Carbon::parse($nextRace->race_start_date)->format('d/m/Y H:i') }}
                            </p>
                        @else
                            <p class="italic text-gray-400">Aucune date disponible</p>
                        @endif

                        <p>Lieu : {{ $raid->raid_place }}</p>

                        <p>Âge minimum : {{ $raid->minAge() ?? '—' }}</p>

                        <p>Nombre de courses : {{ $raid->racesCount() }}</p>

                        @if($raid->isOngoing())
                            <p class="text-green-600 font-semibold">Une course est en cours !</p>
                        @endif
                    </div>

                    {{-- BOUTON --}}
                    <a href="{{ route('raid.show', $raid->raid_id) }}"
                    class="btn-primary mt-auto w-full text-center">
                        Voir le détail
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.getElementById('radius');
            const output = document.getElementById('radius-value');

            slider.oninput = function () {
                output.innerHTML = this.value;
            }
        });
    </script>

@endsection
