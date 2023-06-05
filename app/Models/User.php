<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Http;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public static function guessUserTimezoneUsingAPI($ip)
    {
        $ip = Http::get('https://ipecho.net/' . $ip . '/json');
        if ($ip->json('timezone')) {
            return $ip->json('timezone');
        }
        return null;
    }
}
