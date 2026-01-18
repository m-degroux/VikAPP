{{-- resources/views/components/form/form-base.blade.php --}}
<div class="form-section-container">
    <h2 class="form-section-title">{{ $formTitle }}</h2>
    
    <form action="{{ $action }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            {!! $fields !!} 
        </div>

        <div class="mt-8">
            <button type="submit" class="btn-primary">
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</div>