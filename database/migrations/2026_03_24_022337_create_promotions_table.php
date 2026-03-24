<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->comment('percentage, fixed_amount, free_item, bogo');
            $table->decimal('discount_value', 8, 2)->nullable();
            $table->foreignId('free_item_id')->nullable()->constrained('menu_items')->nullOnDelete();
            $table->string('applicable_to')->default('all')->comment('all, loyalty_members, targeted_segment');
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->boolean('show_on_homepage')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['active', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
