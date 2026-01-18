@extends('layouts.structure')

@section('title', 'Gestion des Raids - VikAPP')

@section('content')

    {{-- Hero Section: Visual header presenting the Organizer dashboard's purpose --}}
    @include('partials.herosection', [
        'pageTitle' => 'Responsable de Raid', 
        'pageSubTitle' => 'Gérez vos événements et organisez vos courses.', 
        'imageUrl' => asset('img/heroSection/header.jpg')
    ])

    <div class="container mx-auto px-4 py-8">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="title-section">Mes Raids</h2>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nom du Raid</th>
                        <th>Lieu</th>
                        <th>Date de début</th>
                        <th>Inscriptions</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Iterates through raids managed by the current user --}}
                    @forelse($raids as $raid)
                        <tr>
                            <td>
                                <a href="{{ route('raid.show', $raid->raid_id) }}" class="text-brand-green hover:underline font-bold text-brand-dark">
                                    {{ $raid->raid_name }}
                                </a>
                                <div class="subtitle">{{ $raid->raid_contact }}</div>
                            </td>
                            <td>
                                <span class="text-gray-600">{{ $raid->raid_place ?? 'Non défini' }}</span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{ \Carbon\Carbon::parse($raid->raid_start_date)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                {{-- Registration Status Logic: Compares current date with registration window --}}
                                @if(\Carbon\Carbon::now()->between($raid->raid_reg_start_date, $raid->raid_reg_end_date))
                                    <span class="badge-status badge-success">Ouvertes</span>
                                @else
                                    <span class="badge-status badge-warning">Fermées</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <!-- Bouton Modifier -->
                                <a href="{{ route('manage.raid.edit', $raid->raid_id ?? $r->raid_id) }}" class="btn btn-sm btn-primary" title="Modifier">
                                    Modifier
                                </a>
                                {{-- Navigation link to add a new race (épreuve) to this specific raid --}}
                                <a href="{{ route('create.race.index', ['raid_id' => $raid->raid_id]) }}" class="btn-table-action">
                                    Ajouter une course
                                </a>
                            </td>
                            
                        </tr>
                    @empty
                        {{-- Fallback UI when the organizer has no assigned events --}}
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500">
                                Aucun raid n'est assigné à votre compte.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection