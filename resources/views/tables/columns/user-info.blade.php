@vite('resources/css/app.css')
<div class="flex items-center justify-center gap-3">
    <span class="text-center">
        {{ $getState()->name }}
    </span>
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $getState()->phone_number) }}"
        target="_blank">@svg('tni-whatsapp', 'w-5 h-5 text-green-600')</a>
</div>
