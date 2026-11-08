<?php

namespace App\Models;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

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
    'inventory_reserved',
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
            'inventory_reserved' => 'boolean',
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
     * @return BelongsToMany<Court, $this>
     */
    public function courts(): BelongsToMany
    {
        return $this->belongsToMany(Court::class)->orderBy('courts.name');
    }

    /**
     * Return every booked court, falling back to the legacy court association.
     *
     * @return EloquentCollection<int, Court>
     */
    public function displayCourts(): EloquentCollection
    {
        if ($this->courts->isNotEmpty()) {
            return $this->courts;
        }

        $legacyCourt = $this->court;

        return $legacyCourt === null
            ? new EloquentCollection
            : new EloquentCollection([$legacyCourt]);
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
     * Scope a query to reservations that include any of the given courts.
     *
     * @param  Builder<Reservation>  $query
     * @param  array<int, int>  $courtIds
     */
    public function scopeForCourts(Builder $query, array $courtIds): void
    {
        $query->where(function (Builder $query) use ($courtIds): void {
            $query->whereIn('court_id', $courtIds)
                ->orWhereHas('courts', function (Builder $query) use ($courtIds): void {
                    $query->whereIn('courts.id', $courtIds);
                });
        });
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

    /**
     * Reserve the equipment quantities attached to this reservation.
     */
    public function reserveEquipmentStock(): void
    {
        if ($this->inventory_reserved) {
            return;
        }

        $reservationEquipment = $this->reservationEquipments()
            ->orderBy('equipment_id')
            ->get();

        if ($reservationEquipment->isEmpty()) {
            return;
        }

        $equipmentIds = $reservationEquipment->pluck('equipment_id')->unique();
        $equipmentById = Equipment::query()
            ->whereKey($equipmentIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($reservationEquipment as $item) {
            $equipment = $equipmentById->get($item->equipment_id);

            if (! $equipment) {
                continue;
            }

            if ($equipment->stock < $item->quantity) {
                throw ValidationException::withMessages([
                    'equipment' => "Insufficient stock for {$equipment->name}.",
                ]);
            }

            $equipment->decrement('stock', $item->quantity);
        }

        $this->update(['inventory_reserved' => true]);
    }

    /**
     * Return the equipment quantities attached to this reservation to inventory.
     */
    public function releaseEquipmentStock(): void
    {
        if (! $this->inventory_reserved) {
            return;
        }

        $reservationEquipment = $this->reservationEquipments()
            ->orderBy('equipment_id')
            ->get();

        $equipmentIds = $reservationEquipment->pluck('equipment_id')->unique();
        $equipmentById = Equipment::query()
            ->whereKey($equipmentIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($reservationEquipment as $item) {
            $equipmentById->get($item->equipment_id)?->increment('stock', $item->quantity);
        }

        $this->update(['inventory_reserved' => false]);
    }

    /**
     * Update reservation status while keeping equipment inventory in sync.
     */
    public function transitionStatus(string $status): void
    {
        if ($this->status === $status) {
            return;
        }

        if ($this->status === 'confirmed' && $status !== 'confirmed') {
            $this->releaseEquipmentStock();
        } elseif ($this->status !== 'confirmed' && $status === 'confirmed') {
            $this->reserveEquipmentStock();
        }

        $this->update(['status' => $status]);
    }
}
