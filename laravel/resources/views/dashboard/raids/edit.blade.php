@extends('layouts.structure')

@section('title', 'Modifier le Raid')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="title-section">Modifier le raid</h1>

        @if ($errors->any())
            <div style="border:1px solid red; padding:10px; margin-bottom:10px;">
                <strong>Erreurs :</strong>
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('manage.raids.update', $raid->raid_id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="form-label">Nom</label>
                    <input type="text" name="raid_name" value="{{ old('raid_name', $raid->raid_name) }}" class="form-input" required>
                </div>

                <div>
                    <label class="form-label">Lieu</label>
                    <input type="text" name="raid_place" value="{{ old('raid_place', $raid->raid_place) }}" class="form-input">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Début inscriptions</label>
                        <input type="datetime-local" name="raid_reg_start_date" value="{{ old('raid_reg_start_date', \Carbon\Carbon::parse($raid->raid_reg_start_date)->format('Y-m-d\TH:i')) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Fin inscriptions</label>
                        <input type="datetime-local" name="raid_reg_end_date" value="{{ old('raid_reg_end_date', \Carbon\Carbon::parse($raid->raid_reg_end_date)->format('Y-m-d\TH:i')) }}" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Responsable du raid</label>
                    <select name="responsible_id" class="form-input">
                        <option value="">-- Aucun (laisser tel quel) --</option>
                        @foreach($clubMembers as $m)
                            <option value="{{ $m->user_id }}" {{ (old('responsible_id') == $m->user_id) || ($raid->managers->contains('user_id', $m->user_id)) ? 'selected' : '' }}>
                                {{ strtoupper($m->mem_name) }} {{ $m->mem_firstname }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-sm mt-2">
                        Voir la liste complète des adhérents : 
                        <a href="{{ route('manage.clubs.edit', $raid->club_id) }}" class="underline text-brand-green">Adhérents du club</a>
                    </p>
                </div>

                <div class="mt-6">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
@endsection