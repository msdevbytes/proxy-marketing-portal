<div class="p-4  dark:bg-gray-700/10 bg-gray-400/20 rounded-md text-center">
    <h3 class="text-2xl font-bold text-center">Bonuses</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 md:grid-cols-5 lg:grid-cols-10 py-8 pb-4">
        @foreach ($bonuses as $item)
            <div class="rounded-md  text-center p-4 dark:bg-yellow-700/10 bg-yellow-400/20">
                <p class="font-semibold text-sm">{{ $item->title }}</p>
                <p class="font-bold text-lg text-yellow-600">
                    {{ Number::format($item->amount, 2) }}
                </p>
            </div>
        @endforeach
    </div>
</div>
