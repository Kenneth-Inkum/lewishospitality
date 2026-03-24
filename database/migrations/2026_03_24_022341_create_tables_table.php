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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->string('name')->comment('e.g. Table 4, Bar Seat 2');
            $table->tinyInteger('capacity');
            $table->string('section')->nullable()->comment('e.g. Main Dining, Patio, Bar');
            $table->float('pos_x')->default(0)->comment('Floor plan X position');
            $table->float('pos_y')->default(0)->comment('Floor plan Y position');
            $table->string('shape')->default('square')->comment('round, square, rectangle');
            $table->string('status')->default('available')->comment('available, reserved, occupied, needs_cleaning');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['location_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
