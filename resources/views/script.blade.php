@php
    $rand = Str::random();
@endphp

<script
    id="laravel-js-store-{{ $rand }}"
    data-store="{{ json_encode(frontend_store()->data()) }}"
>
    var el = document.getElementById('laravel-js-store-{{ $rand }}')
    window.{{ config('js-store.window-element') }} = JSON.parse(el.dataset.store)

@if(config('js-store.remove-data', true))
    delete el.dataset.store
@endif
</script>
