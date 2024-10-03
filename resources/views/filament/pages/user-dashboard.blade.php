@vite('resources/css/app.css')
<x-filament-panels::page>

    <section class="flex flex-col ">
        @livewire('product-stats', ['class' => 'mx-auto md:w-8/12 w-full'], key(Auth::user()->id . Str::random(10)))
    </section>
    <section id="pm-dashboard"
        class="grid md:grid-cols-2 xl:grid-cols-3 lg:grid-cols-2 grid-cols-1 grid-rows-1 md:gap-4 gap-3">

        @foreach (App\Enums\OrderStatus::cases() as $key => $status)
            @livewire('order-stats', ['orderStatus' => $status->value, 'title' => $status->value . ' Summary', 'bgColor' => $status->value], key(Auth::user()->id . $key))
        @endforeach
    </section>

</x-filament-panels::page>
