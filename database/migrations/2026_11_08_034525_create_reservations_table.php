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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('court_rate', 8, 2);
            $table->decimal('court_total', 8, 2);
            $table->decimal('equipment_total', 8, 2)->default(0);
            $table->decimal('total_price', 8, 2);
            $table->string('status')->default('confirmed'); // confirmed, cancelled, completed
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['court_id', 'date', 'status']);
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
