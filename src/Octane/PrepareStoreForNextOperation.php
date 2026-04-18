<?php declare(strict_types=1);

namespace HiHaHo\LaravelJsStore\Octane;

use HiHaHo\LaravelJsStore\Store;

class PrepareStoreForNextOperation
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     */
    public function handle($event): void
    {
        if (! $event->sandbox->resolved(Store::class)) {
            return;
        }

        $event->sandbox->make(Store::class)->flushShared();
    }
}
