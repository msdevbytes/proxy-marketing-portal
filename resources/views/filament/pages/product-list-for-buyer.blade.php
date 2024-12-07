<x-filament-panels::page.simple>
    @vite('resources/css/app.css')
    <section class="container mx-auto my-10 text-center">
        <h1 class="py-10 text-4xl font-bold px-10 bg-gray-50 dark:bg-gray-900 rounded-tl-md rounded-tr-md">HS Masrketing
            Products</h1>
        {{ $this->table }}
    </section>
</x-filament-panels::page.simple>
