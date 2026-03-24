<?php

namespace App\Models;

use Database\Factories\HappyHourFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['location_id', 'day_of_week', 'starts_at', 'ends_at', 'label', 'active'])]
class HappyHour extends Model
{
    /** @use HasFactory<HappyHourFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Location, $this> */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /** @param Builder<HappyHour> $query */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
