<div>
    @if ($getRecord()?->image)
        <img width="300px" src="{{ asset('storage/' . $getRecord()?->image) }}" />
    @endif
</div>
