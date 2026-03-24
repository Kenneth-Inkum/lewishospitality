<?php

namespace App\Models;

use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'menu_category_id', 'name', 'slug', 'description', 'price',
    'dietary_tags', 'featured', 'available_always',
    'available_from', 'available_until', 'available_days',
    'sort_order', 'active', 'pos_id',
])]
class MenuItem extends Model implements HasMedia
{
    /** @use HasFactory<MenuItemFactory> */
    use HasFactory, InteractsWithMedia;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'dietary_tags' => 'array',
            'available_days' => 'array',
            'featured' => 'boolean',
            'available_always' => 'boolean',
            'active' => 'boolean',
            'available_from' => 'date',
            'available_until' => 'date',
        ];
    }

    /** @return BelongsTo<MenuCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    /** @return BelongsToMany<ModifierGroup, $this> */
    public function modifierGroups(): BelongsToMany
    {
        return $this->belongsToMany(ModifierGroup::class, 'menu_item_modifier_groups');
    }

    /** @param Builder<MenuItem> $query */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /** @param Builder<MenuItem> $query */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /**
     * Scope to items available on the given date.
     *
     * @param  Builder<MenuItem>  $query
     */
    public function scopeAvailableOn(Builder $query, Carbon $date): Builder
    {
        return $query->where(function (Builder $q) use ($date): void {
            $q->where('available_always', true)
                ->orWhere(function (Builder $inner) use ($date): void {
                    $inner->where('available_from', '<=', $date)
                        ->where('available_until', '>=', $date);
                });
        });
    }

    /**
     * Full-text search using PostgreSQL tsvector.
     *
     * @param  Builder<MenuItem>  $query
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->whereRaw(
            "to_tsvector('english', name || ' ' || COALESCE(description, '')) @@ plainto_tsquery('english', ?)",
            [$term]
        );
    }

    /**
     * Filter items that have ALL the given dietary tags (PostgreSQL JSONB containment).
     *
     * @param  Builder<MenuItem>  $query
     * @param  array<string>  $tags
     */
    public function scopeWithDietaryTags(Builder $query, array $tags): Builder
    {
        return $query->whereRaw('dietary_tags @> ?::jsonb', [json_encode($tags)]);
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->sharpen(10);
    }
}
