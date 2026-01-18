@extends('layouts.structure')

@section('title', 'VIKAPP - Créer un RAID')

@section('content')

    {{-- 1. Hero Section: Reusable visual header with dynamic page titles --}}
    @include('partials.herosection', [
        'pageTitle' => 'Créer un RAID',
        'pageSubTitle' => 'Ajoutez une nouvelle épreuve au calendrier',
        'imageUrl' => asset('img/raid_thumbnail.png') 
    ])

    {{-- 2. Main Container: Using responsive Tailwind spacing --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Section Title: Visual marker for the start of the form --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 border-l-4 border-brand-green pl-4">
                Formulaire de création
            </h2>
        </div>

        {{-- 3. Raid Creation Form --}}
        <form action="{{ route('raid.store') }}" method="POST">
            @csrf {{-- Mandatory: Laravel CSRF protection --}}

            <div class="space-y-8">
                
                {{-- Fieldset 1: Identity & Manager --}}
                <fieldset class="mb-6 rounded-md border border-gray-200 bg-white p-5 shadow-sm">
                    <legend class="px-2 text-sm font-semibold text-gray-800">Identité du Raid</legend>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="raid_name" class="mb-1 block text-sm font-medium text-gray-700">
                                Nom du Raid :
                            </label>
                            <input id="raid_name" name="raid_name" type="text"
                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                value="{{ old('raid_name') }}" required>
                            @error('raid_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="member_select" class="mb-1 block text-sm font-medium text-gray-700">
                                Responsable du Raid :
                            </label>
                            <select name="responsible_id" id="member_select"
                                    class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                    required>
                                <option value="">-- Sélectionnez un responsable --</option>
                                @foreach($members as $member)
                                    <option
                                        value="{{ $member->user_id }}" {{ old('responsible_id') == $member->user_id ? 'selected' : '' }}>
                                        {{ strtoupper($member->mem_name) }} {{ $member->mem_firstname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('responsible_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Fieldset 2: Dates (Event duration & Registration window) --}}
                <fieldset class="mb-6 rounded-md border border-gray-200 bg-white p-5 shadow-sm">
                    <legend class="px-2 text-sm font-semibold text-gray-800">Dates importantes</legend>

                    <div class="mt-4 space-y-6">
                        <div>
                            <p class="mb-2 text-sm font-semibold text-gray-800">Dates du Raid :</p>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label for="raid_start_date" class="mb-1 block text-sm font-medium text-gray-700">Début :</label>
                                    <input id="raid_start_date" name="raid_start_date" type="date"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-200"
                                        value="{{ old('raid_start_date') }}" required>
                                    @error('raid_start_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="raid_end_date" class="mb-1 block text-sm font-medium text-gray-700">Fin :</label>
                                    <input id="raid_end_date" name="raid_end_date" type="date"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-200"
                                        value="{{ old('raid_end_date') }}" required>
                                    @error('raid_end_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="mb-2 text-sm font-semibold text-gray-800">Inscriptions :</p>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label for="raid_reg_start_date" class="mb-1 block text-sm font-medium text-gray-700">Ouverture :</label>
                                    <input id="raid_reg_start_date" name="raid_reg_start_date" type="date"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-200"
                                        value="{{ old('raid_reg_start_date') }}" required>
                                    @error('raid_reg_start_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="raid_reg_end_date" class="mb-1 block text-sm font-medium text-gray-700">Clôture :</label>
                                    <input id="raid_reg_end_date" name="raid_reg_end_date" type="date"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-200"
                                        value="{{ old('raid_reg_end_date') }}" required>
                                    @error('raid_reg_end_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{-- Fieldset 3: Details & Contact (Location is mandatory) --}}
                <fieldset class="mb-6 rounded-md border border-gray-200 bg-white p-5 shadow-sm">
                    <legend class="px-2 text-sm font-semibold text-gray-800">Détails & Contact</legend>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="raid_contact" class="mb-1 block text-sm font-medium text-gray-700">E-mail de contact :</label>
                            <input id="raid_contact" name="raid_contact" type="email"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-200"
                                value="{{ old('raid_contact') }}" required>
                            @error('raid_contact') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="location" class="mb-1 block text-sm font-medium text-gray-700">Lieu :</label>
                            <input id="location" name="raid_place" type="text"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-200"
                                value="{{ old('raid_place') }}" required>
                            
                            {{-- Server-side validation feedback --}}
                            @error('raid_place') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div>
                            <label for="website" class="mb-1 block text-sm font-medium text-gray-700">Site Web (facultatif) :</label>
                            <input id="website" name="raid_website" type="url"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-200"
                                value="{{ old('website') }}">
                        </div>
                    </div>
                </fieldset>
            </div>

            {{-- Submit Button --}}
            <div class="mt-8 flex justify-end">
                <button type="submit" class="btn-primary px-6 py-3 text-lg font-bold rounded-lg shadow-md bg-brand-green text-white hover:bg-green-700 transition duration-300">
                    Créer le RAID
                </button>
            </div>
        </form>
    </div>

@endsection