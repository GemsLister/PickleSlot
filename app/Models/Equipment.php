<?php

namespace App\Models;

use Database\Factories\EquipmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'category', 'price', 'stock', 'is_active', 'description'])]
class Equipment extends Model
{
    /** @use HasFactory<EquipmentFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<ReservationEquipment, $this>
     */
    public function reservationEquipments(): HasMany
    {
        return $this->hasMany(ReservationEquipment::class);
    }

    /**
     * Scope a query to only include active equipment.
     *
     * @param  Builder<Equipment>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include paddles.
     *
     * @param  Builder<Equipment>  $query
     */
    public function scopePaddles(Builder $query): void
    {
        $query->where('category', 'paddle');
    }

    /**
     * Scope a query to only include balls.
     *
     * @param  Builder<Equipment>  $query
     */
    public function scopeBalls(Builder $query): void
    {
        $query->where('category', 'balls');
    }
}
