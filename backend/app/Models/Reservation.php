<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'user_id',
        'reservation_date',
        'reservation_time',
        'party_size',
        'status',
        'special_requests',
        'contact_phone',
        'contact_email'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'datetime:H:i',
    ];

    /**
     * Get the restaurant that this reservation belongs to.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the user who made this reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include reservations for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('reservation_date', $date);
    }

    /**
     * Scope a query to only include reservations with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
