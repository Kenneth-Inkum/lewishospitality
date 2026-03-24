<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->jsonb('dietary_tags')->nullable()->comment('Array of tag slugs: vegetarian, vegan, gluten_free, halal, spicy, contains_nuts');
            $table->boolean('featured')->default(false);
            $table->boolean('available_always')->default(true);
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();
            $table->jsonb('available_days')->nullable()->comment('Array of day integers: 0=Sunday, 6=Saturday');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->string('pos_id')->nullable()->comment('POS item ID for Phase 2 sync');
            $table->timestamps();

            $table->index('menu_category_id');
            $table->index('featured');
            $table->index('active');
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            // GIN index for dietary tag containment queries: dietary_tags @> '["vegan"]'
            DB::statement('CREATE INDEX menu_items_dietary_tags_gin ON menu_items USING GIN (dietary_tags)');

            // GIN index for full-text search across name + description
            DB::statement("CREATE INDEX menu_items_search_gin ON menu_items USING GIN (to_tsvector('english', name || ' ' || COALESCE(description, '')))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
