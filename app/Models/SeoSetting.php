<?php

namespace App\Models;

use Database\Factories\SeoSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['page', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image'])]
class SeoSetting extends Model
{
    /** @use HasFactory<SeoSettingFactory> */
    use HasFactory;

    /**
     * Retrieve SEO settings for a given page, returning a new unsaved instance if not found.
     */
    public static function forPage(string $page): static
    {
        return static::firstOrNew(['page' => $page]);
    }
}
