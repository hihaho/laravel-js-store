<script>
    window.{{ config('js-store.window-element') }} = @json(frontend_store()->data());
</script>
