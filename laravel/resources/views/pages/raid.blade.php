@extends('layouts.structure')

@section('title', 'VIKAPP - Raids')

@section('content')

    {{-- Hero Section: Visual header for the Raid listing page --}}
    @include('partials.hero-section', [
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

        {{-- Raid Grid: Iterating through the $raids collection --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @foreach($raids as $raid)
                <div class="card-event flex flex-col">

                    {{-- Raid Thumbnail --}}
                    <img
                        src="{{ asset('img/raid_thumbnail.png') }}"
                        class="w-full h-32 object-cover mb-3 rounded"
                    >

                    <h3 class="font-bold text-md mb-1">
                        {{ $raid->raid_name }}
                    </h3>

                    {{-- Raid Metadata: Formatted dates and location --}}
                    <div class="text-[11px] text-gray-500 space-y-0.5 mb-4">
                        <p>Date : {{ \Carbon\Carbon::parse($raid->raid_start_date)->format('d/m/Y') }}</p>
                        <p>Lieu : {{ $raid->raid_place }}</p>
                        {{-- Future feature: Logic to display minimum age based on associated races --}}
                        <p>Âge minimum</p>
                    </div>

                    {{-- Link to individual Raid details --}}
                    <a
                        href="{{ route('raid.show', $raid->raid_id) }}"
                        class="btn-primary mt-auto w-full text-center"
                    >
                        Voir le détail
                    </a>

                </div>
            @endforeach

        </div>

        {{-- Pagination or Load More trigger (Optional) --}}
        <div class="text-center">
            <a href="#" class="btn-outline w-full block">
                Voir l’ensemble des prochains raids
            </a>
        </div>
    </section>

@endsection