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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->date('date');
            $table->time('time');
            $table->unsignedSmallInteger('party_size');
            $table->text('special_requests')->nullable();
            $table->string('status')->default('pending')->comment('pending, confirmed, seated, completed, no_show, cancelled');
            $table->string('source')->default('online_form')->comment('online_form, phone, opentable, resy, walk_in');
            $table->text('notes')->nullable()->comment('Internal staff notes');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();

            $table->index(['location_id', 'date', 'status']);
            $table->index('guest_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
