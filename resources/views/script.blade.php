@php
    $rand = \Illuminate\Support\Str::random();
    $dataId = 'laravel-js-store-data-'.$rand;
    $scriptId = 'laravel-js-store-'.$rand;
@endphp

<script id="{{ $dataId }}" type="application/json">{!! json_encode(frontend_store()->data(), JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script id="{{ $scriptId }}">
    (function () {
        var dataEl = document.getElementById('{{ $dataId }}');
        window.{{ config('js-store.window-element') }} = JSON.parse(dataEl.textContent);
@if(config('js-store.remove-data', true))
        dataEl.remove();
        document.getElementById('{{ $scriptId }}').remove();
@endif
    })();
</script>
