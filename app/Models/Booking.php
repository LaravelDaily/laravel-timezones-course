<?php

namespace App\Models;

use App\Notifications\BookingReminder1H;
use App\Notifications\BookingReminder2H;
use App\Notifications\BookingReminder5MIN;
use App\Notifications\BookingStartedNotification;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'start',
        'end',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheduledNotifications(): MorphMany
    {
        return $this->morphMany(ScheduledNotification::class, 'notifiable');
    }

    public function createReminderNotifications(Booking $booking, CarbonImmutable $startTime): void
    {
        // Schedule 2H reminder
        $twoHoursTime = fromUserDateTime($startTime->subHours(2), $booking->user);
        if (now('UTC')->lessThan($twoHoursTime)) {
            $booking->user->scheduledNotifications()->create([
                'notification_class' => BookingReminder2H::class,
                'notifiable_id' => $booking->id,
                'notifiable_type' => __CLASS__,
                'sent' => false,
                'processing' => false,
                'scheduled_at' => $twoHoursTime,
                'sent_at' => null,
                'tries' => 0,
            ]);
        }
        // Schedule 1H reminder
        $oneHourTime = fromUserDateTime($startTime->subHour(), $booking->user);
        if (now('UTC')->lessThan($oneHourTime)) {
            $booking->user->scheduledNotifications()->create([
                'notification_class' => BookingReminder1H::class,
                'notifiable_id' => $booking->id,
                'notifiable_type' => __CLASS__,
                'sent' => false,
                'processing' => false,
                'scheduled_at' => $oneHourTime,
                'sent_at' => null,
                'tries' => 0,
            ]);
        }
        // Schedule 5 min reminder
        $fiveMinutesTime = fromUserDateTime($startTime->subMinutes(5), $booking->user);
        if (now('UTC')->lessThan($fiveMinutesTime)) {
            $booking->user->scheduledNotifications()->create([
                'notification_class' => BookingReminder5MIN::class,
                'notifiable_id' => $booking->id,
                'notifiable_type' => __CLASS__,
                'sent' => false,
                'processing' => false,
                'scheduled_at' => $fiveMinutesTime,
                'sent_at' => null,
                'tries' => 0,
            ]);
        }
        // Schedule started reminder
        $startingTime = fromUserDateTime($startTime, $booking->user);
        if (now('UTC')->lessThan($startingTime)) {
            $booking->user->scheduledNotifications()->create([
                'notification_class' => BookingStartedNotification::class,
                'notifiable_id' => $booking->id,
                'notifiable_type' => __CLASS__,
                'sent' => false,
                'processing' => false,
                'scheduled_at' => $startingTime,
                'sent_at' => null,
                'tries' => 0,
            ]);
        }
    }
}
