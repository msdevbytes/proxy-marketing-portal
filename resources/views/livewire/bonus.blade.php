<div class="px-4 py-2  bg-gray-200/60 dark:bg-gray-900 rounded-md">

    <h3 class="text-2xl font-bold ">Bonuses</h3>
    <div
        class="grid grid-flow-row xs:grid-cols-1 sm:grid-cols-2 gap-2 md:grid-cols-3 lg:grid-cols-5 items-center md:justify-start py-8 pb-4 ">
        @foreach ($bonuses as $item)
            <div class="rounded-md dark:bg-yellow-700/10 bg-yellow-400/20 text-center p-4">
                <p class="font-semibold text-lg">{{ $item->title }}</p>
                <p class="font-bold text-3xl text-yellow-600">
                    {{ Number::format($item->amount, 2) }}
                </p>
            </div>
        @endforeach
    </div>
</div>
