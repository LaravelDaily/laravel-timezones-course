<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;

class BookingCreatedEvent
{
    use Dispatchable;

    public function __construct(public Booking $booking)
    {
    }
}
