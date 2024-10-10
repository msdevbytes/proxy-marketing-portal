<div>
    @if ($getRecord()?->amazone_image)
        <img width="300px" src="{{ asset('storage/' . $getRecord()?->amazone_image) }}" />
    @endif
</div>
