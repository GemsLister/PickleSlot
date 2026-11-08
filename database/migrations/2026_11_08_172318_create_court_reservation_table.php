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
        Schema::create('court_reservation', function (Blueprint $table) {
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->primary(['court_id', 'reservation_id']);
        });

        DB::table('reservations')
            ->select(['id', 'court_id'])
            ->orderBy('id')
            ->chunk(500, function ($reservations): void {
                DB::table('court_reservation')->insert(
                    $reservations->map(fn (object $reservation): array => [
                        'court_id' => $reservation->court_id,
                        'reservation_id' => $reservation->id,
                    ])->all()
                );
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_reservation');
    }
};
