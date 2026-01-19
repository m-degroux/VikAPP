@props(['id', 'title', 'date', 'location', 'age', 'image'])

<div class="card-event">
    <img src="{{ $image ?? '/img/raid_thumbnail.png' }}" class="w-full h-32 object-cover mb-3">
    
    <h3 class="font-bold text-md mb-1">{{ $title }}</h3>
    
    <div class="text-[11px] text-gray-500 space-y-0.5">
        <p>Date : {{ $date }}</p>
        <p>Lieu : {{ $location }}</p>
        <p>Âge : {{ $age }}</p>
    </div>

    {{-- Lien dynamique vers la route 'courses.show' --}}
    <a href="{{ route('courses.show', $id) }}" class="btn-primary mt-4 w-full text-center block">
        Voir le détail
    </a>
</div>