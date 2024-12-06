@vite('resources/js/app.js')
<link href="https://cdn.jsdelivr.net/npm/simplelightbox@2.14.3/dist/simple-lightbox.min.css" rel="stylesheet">
<div class="p-10 rounded-md text-center">
    <h1 class="text-2xl font-bold text-center">Offers</h1>
    <div class="lightbox grid grid-cols-1 sm:grid-cols-3 gap-2 md:grid-cols-5 lg:grid-cols-4 py-8 pb-4 text-left">
        @foreach ($offers as $item)
            <div class="flex gap-4 flex-col rounded-md p-4 dark:bg-yellow-700/10 bg-yellow-400/20">
                <p>{{ $item->title }}</p>
                {!! nl2br($item->description) !!}
                <p class="font-bold text-3xl">{{ $item->price }}</p>

                <a class="lightbox-img" href="{{ asset('storage/' . $item?->image) }}" title="{{ $item->title }}">
                    <img src="{{ asset('storage/' . $item?->image) }}" />
                </a>
            </div>
        @endforeach
    </div>
    {{-- In work, do what you enjoy. --}}
</div>
