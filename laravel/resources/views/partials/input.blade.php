<div>
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>

    <input 
        id="{{ $name }}" 
        name="{{ $name }}" 
        type="{{ $type ?? 'text' }}" 
        class="form-input mt-1"
        value="{{ old($name, $value) }}" 
        {{ $required ?? '' }} 
        {{ $autofocus ?? '' }}
        autocomplete="{{ $autocomplete ?? $name }}" 
    />

    @error($name)
        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>