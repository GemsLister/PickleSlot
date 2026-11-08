<?php

namespace App\Models;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'court_id',
    'date',
    'start_time',
    'end_time',
    'court_rate',
    'court_total',
    'equipment_total',
    'total_price',
    'status',
    'notes',
])]
class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'court_rate' => 'decimal:2',
            'court_total' => 'decimal:2',
            'equipment_total' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Court, $this>
     */
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * @return HasMany<ReservationEquipment, $this>
     */
    public function reservationEquipments(): HasMany
    {
        return $this->hasMany(ReservationEquipment::class);
    }

    /**
     * @return BelongsToMany<Equipment, $this>
     */
    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'reservation_equipment')
            ->withPivot(['quantity', 'unit_price', 'total_price'])
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active (non-cancelled) reservations.
     *
     * @param  Builder<Reservation>  $query
     */
    public function scopeActiveStatus(Builder $query): void
    {
        $query->where('status', '!=', 'cancelled');
    }

    /**
     * Check if this reservation can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        if ($this->status === 'cancelled') {
            return false;
        }

        $startDateTime = $this->date->format('Y-m-d').' '.$this->start_time;

        return now()->lt($startDateTime);
    }
}
