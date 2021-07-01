<?php

namespace HiHaHo\LaravelJsStore\Octane;

use HiHaHo\LaravelJsStore\Store;

class PrepareStoreForNextOperation
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event): void
    {
        if (! $event->sandbox->resolved(Store::class)) {
            return;
        }

        $store = $event->sandbox->make(Store::class);

        if (method_exists($store, 'flushShared')) {
            $store->flushShared();
        }
    }
}
