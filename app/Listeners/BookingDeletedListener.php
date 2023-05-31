<?php

namespace App\Listeners;

use App\Events\BookingDeletedEvent;

class BookingDeletedListener
{
    public function __construct()
    {
    }

    public function handle(BookingDeletedEvent $event): void
    {
        $event->booking->scheduledNotifications()
            ->where('user_id', $event->booking->user_id)
            ->delete();
    }
}
