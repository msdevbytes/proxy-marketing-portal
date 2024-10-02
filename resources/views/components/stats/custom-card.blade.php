<div class="flex flex-col rounded-md overflow-hidden shadow-md">
    <div @class(['p-5 text-white bg-gradient-to-t', $bgColor])>
        <p class="text-lg">{{ $title ?? '' }}</p>
        @if (isset($header))
            <div @class([
                'flex text-center flex-col pt-4 gap-1',
                'pb-10' => isset($data) ? true : false,
                'pb-4' => isset($data) ? false : true,
            ])>
                @foreach ($header as $key => $value)
                    <span>{{ $key }}</span>
                    <span class="font-bold text-3xl">{{ $value }}</span>
                @endforeach
            </div>
        @else
            <div class="my-10"></div>
        @endif

    </div>
    @isset($data)
        <div
            class="flex bg-gradient-to-tr from-gray-200 to-gray-50 dark:bg-gradient-to-tr dark:from-gray-900 dark:to-gray-600 p-5">
            <div
                class="flex flex-col gap-3 w-full md:-mt-16 -mt-14 font-semibold dark:text-gray-200 text-gray-900 md:text-base">
                @foreach ($data as $item)
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg($item['icon'], 'w-8 h-8')
                            <p>{{ $item['title'] }}</p>
                        </div>
                        <p class="md:text-base text-3xl">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endisset
</div>
