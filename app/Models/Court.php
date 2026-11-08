<?php

namespace App\Models;

use Database\Factories\CourtFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'type', 'surface', 'hourly_rate', 'is_active', 'description', 'image_path'])]
class Court extends Model
{
    /** @use HasFactory<CourtFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hourly_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the fully qualified URL for this court image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    /**
     * @return BelongsToMany<Reservation, $this>
     */
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class);
    }

    /**
     * Scope a query to only include active courts.
     *
     * @param  Builder<Court>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
