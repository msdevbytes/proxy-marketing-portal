@vite('resources/css/app.css')
<x-filament-panels::page>

    @if (Auth::user()->isPm() || Auth::useR()->isSuperAdmin())
        <section>
            @livewire('bonus', key('bonus-' . Auth::user()->id . Str::random(10)))
        </section>
    @endif

    <section class="flex flex-col">
        @livewire('product-stats', ['class' => 'mx-auto md:w-8/12 w-full'], key('product-stats-' . Auth::user()->id . Str::random(10)))
    </section>
    <section id="pm-dashboard"
        class="grid md:grid-cols-2 xl:grid-cols-3 lg:grid-cols-2 grid-cols-1 grid-rows-1 md:gap-4 gap-3">

        @foreach (App\Enums\OrderStatus::cases() as $key => $status)
            <a
                href="{{ route('filament.admin.resources.orders.index', ['tableFilters[status][value]' => 'Ordered', 'tableFilters[orders][status][value]' => $status->value]) }}">
                @livewire('order-stats', ['orderStatus' => $status->value, 'title' => $status->value . ' Summary', 'bgColor' => $status->value], key('order-stats-' . Auth::user()->id . $key))
            </a>
        @endforeach
    </section>

</x-filament-panels::page>
