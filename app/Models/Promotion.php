<?php

namespace App\Models;

use Database\Factories\PromotionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'location_id', 'title', 'description', 'type',
    'discount_value', 'free_item_id', 'applicable_to',
    'starts_at', 'ends_at', 'show_on_homepage', 'active',
])]
class Promotion extends Model
{
    /** @use HasFactory<PromotionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'starts_at' => 'date',
            'ends_at' => 'date',
            'show_on_homepage' => 'boolean',
            'active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Location, $this> */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /** @return BelongsTo<MenuItem, $this> */
    public function freeItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'free_item_id');
    }

    /** @param Builder<Promotion> $query */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    /** @param Builder<Promotion> $query */
    public function scopeShowOnHomepage(Builder $query): Builder
    {
        return $query->where('show_on_homepage', true);
    }
}
