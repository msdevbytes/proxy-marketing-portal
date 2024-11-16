<div class="px-10 py-6  bg-gray-200/60 dark:bg-gray-900 rounded-md">

    <h3 class="text-2xl font-bold ">Bonuses</h3>
    <div class="flex items-center justify-start w-full py-8 pb-4">
        @foreach ($bonuses as $item)
            <div class="border-l px-10 first:pl-0 first:border-none dark:border-yellow-700/70 border-yellow-400/70">
                <p class="font-semibold text-lg">{{ $item->title }}</p>
                <p class="font-bold text-3xl text-yellow-600">
                    {{ Number::format($item->amount, 2) }}
                </p>
            </div>
        @endforeach
    </div>
</div>
