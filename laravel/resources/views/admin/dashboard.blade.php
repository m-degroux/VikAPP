@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">
                    Dashboard Administration
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-sm font-medium text-blue-800">Total Raids</h3>
                        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $raidsCount ?? 0 }}</p>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6">
                        <h3 class="text-sm font-medium text-green-800">Total Courses</h3>
                        <p class="text-3xl font-bold text-green-900 mt-2">{{ $racesCount ?? 0 }}</p>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-6">
                        <h3 class="text-sm font-medium text-purple-800">Total Clubs</h3>
                        <p class="text-3xl font-bold text-purple-900 mt-2">{{ $clubsCount ?? 0 }}</p>
                    </div>

                    <div class="bg-orange-50 rounded-lg p-6">
                        <h3 class="text-sm font-medium text-orange-800">Total Membres</h3>
                        <p class="text-3xl font-bold text-orange-900 mt-2">{{ $membersCount ?? 0 }}</p>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-6 shadow">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Derniers Raids</h2>
                        @if($recentRaids->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentRaids as $raid)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                                        <h3 class="font-semibold text-gray-900">{{ $raid->raid_name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $raid->raid_place }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Club: {{ $raid->club?->club_name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($raid->raid_start_date)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Aucun raid récent</p>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Activité Récente</h2>
                        @if($recentActivity->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentActivity as $activity)
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            @if($activity['type'] === 'raid')
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <p class="text-sm font-semibold text-gray-900">{{ $activity['title'] }}</p>
                                            <p class="text-xs text-gray-600">{{ $activity['description'] }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                @if($activity['type'] === 'raid')
                                                    Club: {{ $activity['club'] }}
                                                @else
                                                    Raid: {{ $activity['raid'] }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Aucune activité récente</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
