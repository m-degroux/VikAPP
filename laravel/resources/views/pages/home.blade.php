@extends('layouts.structure')

@section('title', 'VIKAPP - Accueil')

@section('content')

    {{-- Hero Section: Commented out as per user request --}}
    {{--  @include('partials.hero-section', ['pageTitle' => 'Vik\'App', 'pageSubTitle' => 'Tout votre club dans votre poche.', 'imageUrl' => asset('img/heroSection/header.jpg')]) --}}

    <section class="max-w-6xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <p class="subtitle italic">Bienvenue sur Vik'App</p>
            <h2 class="title-section">L'expertise de l'orientation au service de votre performance.</h2>
            <p class="text-gray-600 leading-relaxed text-sm mb-6">
                Conçue exclusivement pour la communauté Vik'azim, Vik'App est la plateforme numérique de référence pour la gestion et le suivi des sports d'orientation en Normandie. Que vous soyez un athlète passionné, un responsable de club ou un organisateur de raids, Vik'App centralise tous vos besoins en une interface unique et intuitive.
            </p>
            <a href="#raid" class="btn-primary">Trouver un raid</a>
        </div>
        <div>
        
    <video 
        autoplay 
        muted 
        loop 
        playsinline 
        poster="{{ asset('img/trail.png') }}" 
        class="w-full h-[450px] object-cover rounded-3xl shadow-lg"
    >
        <source src="{{ asset('videos/background2.mp4') }}" type="video/mp4">
        Votre navigateur ne supporte pas la vidéo.
    </video>
</div>
    </section>
    <!-- Section prochains raids To do : modifier dynamiquement les courses à venir, voir second commentaire -->
     <div id ="raid"></div>
    <section class="max-w-6xl mx-auto px-6 py-12">
        <div class="flex flex-row gap-12 items-start">
            <div class="hidden md:block w-1/3 ">
            
        </div>
    </section>

    <div id="raid"></div>
    <section class="max-w-6xl mx-auto px-6 py-12">
        <div class="flex flex-row gap-12 items-start">
            <div class="hidden md:block w-1/3">
                {{-- Side Video Background --}}
                <video 
                    autoplay 
                    muted 
                    loop 
                    playsinline 
                    poster="{{ asset('images/fallback-bg.jpg') }}" 
                    class="w-full h-[600px] rounded-3xl object-cover rounded"
                >
                    <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
                    Votre navigateur ne supporte pas la vidéo.
                </video>
            </div>
            
            <div class="w-full md:w-2/3">
                <p class="subtitle italic">À venir</p>
                <h2 class="title-section">Prochains raids</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    {{-- Loop: Displaying the next 3 raids retrieved from the Controller --}}
                    @foreach($nextRaids as $raid)
                        @php
                            // Custom methods assumed in Raid Model to handle race logic
                            $nextRace = $raid->nextRace();
                            $timeUntil = $raid->timeUntilNextRace();
                        @endphp

                        <div class="card-event flex flex-col relative">

                            {{-- Dynamic countdown or "Next Race" label --}}
                            @if($timeUntil)
                                <div class="absolute top-2 left-2 bg-green-700 text-white text-[12px] px-2 py-1 rounded shadow z-10">
                                    <strong>Prochaine course : {{ $timeUntil }}</strong>
                                </div>
                            @endif

                            <img src="{{ asset('img/raid_thumbnail.png') }}" class="w-full h-32 object-cover mb-3 rounded">

                            <h3 class="font-bold text-md mb-1">{{ $raid->raid_name }}</h3>

                            <div class="text-[11px] text-gray-500 space-y-0.5 mb-4">
                                {{-- Race Metadata --}}
                                @if($nextRace)
                                    <p>Date : {{ \Carbon\Carbon::parse($nextRace->race_start_date)->format('d/m/Y H:i') }}</p>
                                @endif

                                <p>Lieu : {{ $raid->raid_place }}</p>

                                <p>Âge minimum : {{ $raid->min_age ?? '—' }}</p>

                                <p>Nombre de courses : {{ $raid->races->count() }}</p>

                                {{-- Real-time Status --}}
                                @if($raid->isOngoing())
                                    <p class="text-green-600 font-semibold">Une course est en cours !</p>
                                @endif
                            </div>

                            {{-- Link to individual Raid Detail Page --}}
                            <a href="{{ route('raid.show', $raid->raid_id) }}"
                            class="btn-primary mt-auto w-full text-center">
                                Voir le détail
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Link to Global Archive --}}
                <div class="text-center">
                    <a href="{{ route('raid.index') }}" class="btn-outline w-full block">
                        Voir l'ensemble des prochains raids.
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection