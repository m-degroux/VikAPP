@props(['club'])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">
                {{ $club->club_name }}
            </h3>
            @if($club->club_active)
                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                    Actif
                </span>
            @else
                <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">
                    Inactif
                </span>
            @endif
        </div>
        
        <div class="space-y-2 text-sm text-gray-600">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                <span>{{ $club->club_address ?? 'Adresse non renseignée' }}</span>
            </div>
        </div>

        <div class="mt-4 flex space-x-2">
            <a href="{{ route('clubs.edit', $club->club_id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                Éditer
            </a>
        </div>
    </div>
</div>
