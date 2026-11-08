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
        Schema::table('reservations', function (Blueprint $table) {
            $table->boolean('inventory_reserved')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $reservedEquipment = DB::table('reservation_equipment')
            ->join('reservations', 'reservation_equipment.reservation_id', '=', 'reservations.id')
            ->where('reservations.inventory_reserved', true)
            ->groupBy('reservation_equipment.equipment_id')
            ->selectRaw('reservation_equipment.equipment_id, SUM(reservation_equipment.quantity) as quantity')
            ->get();

        foreach ($reservedEquipment as $item) {
            DB::table('equipment')
                ->where('id', $item->equipment_id)
                ->increment('stock', $item->quantity);
        }

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('inventory_reserved');
        });
    }
};
