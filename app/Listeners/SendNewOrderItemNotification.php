<?php

namespace App\Listeners;

use App\Events\OrderItemCreated;
use App\Notifications\NewOrderItemNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewOrderItemNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderItemCreated $event): void
    {
        $vendor = $event->orderItem->vendor;
        $vendor->notify(new NewOrderItemNotification($event->orderItem));
    }
}
