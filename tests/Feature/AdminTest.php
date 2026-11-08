<?php

use App\Models\Court;
use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\ReservationEquipment;
use App\Models\User;
use Database\Seeders\EquipmentSeeder;

test('non-admin players cannot access admin dashboard', function () {
    $player = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($player)->get(route('admin.dashboard'));

    $response->assertStatus(403);
});

test('admin can access admin dashboard with statistics', function () {
    $admin = User::factory()->admin()->create();
    Court::factory()->count(2)->create(['is_active' => true]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200)
        ->assertSee('Facility Admin')
        ->assertSee('PickleSlot Overview');
});

test('admin can create a new court', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('admin.courts.store'), [
        'name' => 'Court 7 - Skyview Stadium',
        'type' => 'indoor',
        'surface' => 'Pro-Cushion Acrylic',
        'hourly_rate' => 40.00,
        'description' => 'Olympic regulation pickleball court.',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.courts.index'));

    $this->assertDatabaseHas('courts', [
        'name' => 'Court 7 - Skyview Stadium',
        'type' => 'indoor',
        'hourly_rate' => 40.00,
        'is_active' => true,
    ]);
});

test('admin can toggle court active status', function () {
    $admin = User::factory()->admin()->create();
    $court = Court::factory()->create(['is_active' => true]);

    $response = $this->actingAs($admin)->patch(route('admin.courts.toggle', $court));

    $response->assertSessionHas('status');
    expect($court->fresh()->is_active)->toBeFalse();
});

test('admin can create and edit equipment item', function () {
    $admin = User::factory()->admin()->create();

    // Create
    $response = $this->actingAs($admin)->post(route('admin.equipment.store'), [
        'name' => 'Pro Spin Paddle 3000',
        'category' => 'paddle',
        'price' => 7.50,
        'stock' => 15,
        'description' => 'High-grit carbon texture.',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.equipment.index'));

    $equipment = Equipment::where('name', 'Pro Spin Paddle 3000')->first();
    expect($equipment)->not->toBeNull();
    expect((float) $equipment->price)->toBe(7.50);
    expect($equipment->stock)->toBe(15);

    // Edit
    $updateResponse = $this->actingAs($admin)->put(route('admin.equipment.update', $equipment), [
        'name' => 'Pro Spin Paddle 3000 v2',
        'category' => 'paddle',
        'price' => 8.00,
        'stock' => 20,
        'description' => 'Updated specs.',
        'is_active' => '1',
    ]);

    $updateResponse->assertRedirect(route('admin.equipment.index'));
    expect($equipment->fresh()->name)->toBe('Pro Spin Paddle 3000 v2');
    expect((float) $equipment->fresh()->price)->toBe(8.00);
});

test('seeding equipment preserves the current available stock', function () {
    $this->seed(EquipmentSeeder::class);
    $paddle = Equipment::where('name', 'Pro Carbon Fiber Paddle (Selkirk Vanguard)')->firstOrFail();
    $paddle->update(['stock' => 14]);

    $this->seed(EquipmentSeeder::class);

    expect($paddle->fresh()->stock)->toBe(14);
});

test('admin can update reservation status', function () {
    $admin = User::factory()->admin()->create();
    $reservation = Reservation::factory()->create([
        'status' => 'confirmed',
        'inventory_reserved' => true,
    ]);
    $paddle = Equipment::factory()->paddle()->create(['stock' => 3]);
    ReservationEquipment::create([
        'reservation_id' => $reservation->id,
        'equipment_id' => $paddle->id,
        'quantity' => 2,
        'unit_price' => $paddle->price,
        'total_price' => (float) $paddle->price * 2,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.reservations.status', $reservation), [
        'status' => 'completed',
    ]);

    $response->assertSessionHas('status');
    expect($reservation->fresh()->status)->toBe('completed');
    expect($paddle->fresh()->stock)->toBe(5);

    $this->actingAs($admin)->patch(route('admin.reservations.status', $reservation), [
        'status' => 'completed',
    ]);
    expect($paddle->fresh()->stock)->toBe(5);

    $this->actingAs($admin)->patch(route('admin.reservations.status', $reservation), [
        'status' => 'confirmed',
    ]);
    expect($paddle->fresh()->stock)->toBe(3);
});
