{{-- <x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        <img x-src="state" />
    </div>
</x-dynamic-component> --}}

<div>
    <img width="300px" src="{{ asset('storage/' . $getRecord()->invoice_image) }}" />
</div>
