@extends('layouts.structure')

@section('content')
    <section class="max-w-4xl mx-auto my-10 px-4 font-sans">
        <h2 class="title-section">Clubs référencés</h2>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>NOM DU CLUB</th>
                        <th>ADRESSE</th>
                        <th>RESPONSABLE</th>
                        <th class="text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clubs as $club)
                        <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <td class="py-4 px-3 font-bold text-gray-800"> {{ $club->club_name }} </td>
                            <td class="text-gray-600"> {{ $club->club_adress }} </td>
                            <td class="text-gray-600">
                                @if ($club->manager)
                                    {{ $club->manager->mem_firstname }} {{ strtoupper($club->manager->mem_name) }}
                                @else
                                    <span class="text-gray-400 italic text-xs">Aucun responsable</span>
                                @endif
                            </td>
                            <td class="text-right px-3">
                                <form method="POST" action="{{ route('manage.club.destroy', ['club' => $club->club_id]) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-table-action text-red-600 border-red-600 hover:bg-red-600">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                    </tr> @empty <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">Aucun club référencé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
