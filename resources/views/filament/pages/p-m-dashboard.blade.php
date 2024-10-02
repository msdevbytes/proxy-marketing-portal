@vite('resources/css/app.css')
<x-filament-panels::page>

    <section id="pm-dashboard"
        class="grid md:grid-cols-2 xl:grid-cols-3 lg:grid-cols-2 grid-cols-1 grid-rows-1 md:gap-4 gap-3">
        @livewire('product-stats', key(Auth::user()->id . Str::random(10)))
        @livewire('order-stats', key(Auth::user()->id))
    </section>

</x-filament-panels::page>
