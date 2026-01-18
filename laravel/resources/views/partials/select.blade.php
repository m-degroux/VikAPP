<div class="mt-4">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>

    <select id="{{ $name }}" name="{{ $name }}" 
        class="form-input mt-1 block w-full">
        
        @foreach($options as $club)
            <option value="{{ $club->club_id }}" 
                {{ (old($name, $value) == $club->club_id) ? 'selected' : '' }}>
                {{ $club->club_name }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-red-600 text-[11px] font-bold uppercase mt-1 tracking-wide">
            {{ $message }}
        </p>
    @enderror
</div>