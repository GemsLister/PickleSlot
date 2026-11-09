<?php

use App\Models\Court;
use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\User;

test('reservation create page is accessible by authenticated users', function () {
    $user = User::factory()->create();
    Court::factory()->create();

    $response = $this->actingAs($user)->get(route('reservations.create'));

    $response->assertOk()
        ->assertSee('id="durationHours"', false)
        ->assertSee('name="court_ids[]"', false)
        ->assertSee('value="2"', false);
});

test('administrators cannot access the player booking page', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('reservations.create'));

    $response->assertForbidden();
});

test('administrators cannot submit a player booking', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('reservations.store'), []);

    $response->assertForbidden();
    $this->assertDatabaseCount('reservations', 0);
});

test('administrators see admin navigation instead of player navigation', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('profile.edit'));

    $response->assertOk()
        ->assertSee('Admin Center')
        ->assertSee('My Profile')
        ->assertDontSee(route('reservations.create'));
});

test('user can reserve a court with paddle and balls rental', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create([
        'hourly_rate' => 30.00,
        'is_active' => true,
    ]);

    $paddle = Equipment::factory()->paddle()->create([
        'price' => 6.00,
        'stock' => 10,
    ]);

    $balls = Equipment::factory()->balls()->create([
        'price' => 3.50,
        'stock' => 15,
    ]);

    $tomorrow = now()->addDays(2)->format('Y-m-d');

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'court_id' => $court->id,
        'date' => $tomorrow,
        'start_time' => '10:00',
        'end_time' => '11:00',
        'duration_hours' => 1,
        'notes' => 'Please have paddles ready near court entrance.',
        'equipment' => [
            $paddle->id => 2,
            $balls->id => 3,
        ],
    ]);

    $reservation = Reservation::first();
    expect($reservation)->not->toBeNull();

    $response->assertRedirect(route('reservations.show', $reservation));

    expect((float) $reservation->court_rate)->toBe(30.00);
    expect((float) $reservation->court_total)->toBe(30.00);
    expect((float) $reservation->equipment_total)->toBe(22.50); // (2 * 6.00) + (3 * 3.50) = 22.50
    expect((float) $reservation->total_price)->toBe(52.50); // 30.00 + 22.50 = 52.50
    expect($reservation->status)->toBe('confirmed');

    $this->assertDatabaseHas('reservation_equipment', [
        'reservation_id' => $reservation->id,
        'equipment_id' => $paddle->id,
        'quantity' => 2,
        'total_price' => 12.00,
    ]);

    $this->assertDatabaseHas('reservation_equipment', [
        'reservation_id' => $reservation->id,
        'equipment_id' => $balls->id,
        'quantity' => 3,
        'total_price' => 10.50,
    ]);

    expect($paddle->fresh()->stock)->toBe(8);
    expect($balls->fresh()->stock)->toBe(12);
    expect($reservation->fresh()->inventory_reserved)->toBeTrue();
});

test('user can reserve multiple courts in one booking', function () {
    $user = User::factory()->create();
    $court1 = Court::factory()->create([
        'hourly_rate' => 30.00,
        'is_active' => true,
    ]);
    $court2 = Court::factory()->create([
        'hourly_rate' => 25.00,
        'is_active' => true,
    ]);
    $date = now()->addDays(2)->format('Y-m-d');

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'court_ids' => [$court1->id, $court2->id],
        'date' => $date,
        'start_time' => '10:00',
        'end_time' => '12:00',
        'duration_hours' => 2,
    ]);

    $reservation = Reservation::first();
    expect($reservation)->not->toBeNull();

    $response->assertRedirect(route('reservations.show', $reservation));
    expect((float) $reservation->court_rate)->toBe(55.00);
    expect((float) $reservation->court_total)->toBe(110.00);
    $reservedCourtIds = $reservation->courts->modelKeys();
    sort($reservedCourtIds);
    $expectedCourtIds = [$court1->id, $court2->id];
    sort($expectedCourtIds);
    expect($reservedCourtIds)->toBe($expectedCourtIds);

    $this->assertDatabaseHas('court_reservation', [
        'reservation_id' => $reservation->id,
        'court_id' => $court1->id,
    ]);
    $this->assertDatabaseHas('court_reservation', [
        'reservation_id' => $reservation->id,
        'court_id' => $court2->id,
    ]);

    $this->actingAs($user)
        ->get(route('reservations.show', $reservation))
        ->assertOk()
        ->assertSee($court1->name)
        ->assertSee($court2->name);

    $this->get(route('courts.show', $court2))
        ->assertOk()
        ->assertSee('Booked: 10:00 - 12:00');

    $this->get(route('api.availability', ['court_id' => $court2->id, 'date' => $date]))
        ->assertOk()
        ->assertJsonPath('slots.0.start', '10:00')
        ->assertJsonPath('slots.0.end', '12:00');

    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)
        ->get(route('admin.reservations.index', ['court_id' => $court2->id]))
        ->assertOk()
        ->assertSee($court1->name)
        ->assertSee($court2->name);
});

test('cannot reserve multiple courts when any selected court is already booked', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $court1 = Court::factory()->create();
    $court2 = Court::factory()->create();
    $date = now()->addDays(3)->format('Y-m-d');

    Reservation::factory()->create([
        'user_id' => $user1->id,
        'court_id' => $court2->id,
        'date' => $date,
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($user2)->from(route('reservations.create'))->post(route('reservations.store'), [
        'court_ids' => [$court1->id, $court2->id],
        'date' => $date,
        'start_time' => '11:00',
        'end_time' => '12:00',
        'duration_hours' => 1,
    ]);

    $response->assertSessionHasErrors(['start_time']);
    expect(Reservation::count())->toBe(1);
});

test('reservation is rejected when a selected court is inactive', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create(['is_active' => false]);

    $response = $this->actingAs($user)->from(route('reservations.create'))->post(route('reservations.store'), [
        'court_ids' => [$court->id],
        'date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'duration_hours' => 1,
    ]);

    $response->assertSessionHasErrors(['court_ids.0']);
    expect(Reservation::count())->toBe(0);
});

test('reservation is rejected when no court is selected', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->from(route('reservations.create'))->post(route('reservations.store'), [
        'date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'duration_hours' => 1,
    ]);

    $response->assertSessionHasErrors(['court_ids']);
    expect(Reservation::count())->toBe(0);
});

test('user can reserve multiple consecutive hours and is charged for each hour', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create([
        'hourly_rate' => 32.00,
        'is_active' => true,
    ]);

    $date = now()->addDays(2)->format('Y-m-d');

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'court_id' => $court->id,
        'date' => $date,
        'start_time' => '10:00',
        'end_time' => '12:00',
        'duration_hours' => 2,
    ]);

    $reservation = Reservation::first();
    expect($reservation)->not->toBeNull();

    $response->assertRedirect(route('reservations.show', $reservation));
    expect((float) $reservation->court_total)->toBe(64.00);
    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'start_time' => '10:00',
        'end_time' => '12:00',
        'court_total' => 64.00,
    ]);

    $this->actingAs($user)
        ->get(route('reservations.show', $reservation))
        ->assertOk()
        ->assertSee('Court Fee (10:00–12:00 @ $32.00/hr):');
});

test('reservation is rejected when selected duration does not match the requested hours', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create();

    $response = $this->actingAs($user)->from(route('reservations.create'))->post(route('reservations.store'), [
        'court_id' => $court->id,
        'date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'duration_hours' => 3,
    ]);

    $response->assertSessionHasErrors(['end_time']);
    expect(Reservation::count())->toBe(0);
});

test('reservation is rejected outside facility operating hours', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create();

    $response = $this->actingAs($user)->from(route('reservations.create'))->post(route('reservations.store'), [
        'court_id' => $court->id,
        'date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '06:00',
        'end_time' => '08:00',
        'duration_hours' => 2,
    ]);

    $response->assertSessionHasErrors(['start_time']);
    expect(Reservation::count())->toBe(0);
});

test('reservation is rejected when selected times do not begin on whole hours', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create();

    $response = $this->actingAs($user)->from(route('reservations.create'))->post(route('reservations.store'), [
        'court_id' => $court->id,
        'date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '10:30',
        'end_time' => '11:30',
        'duration_hours' => 1,
    ]);

    $response->assertSessionHasErrors(['end_time']);
    expect(Reservation::count())->toBe(0);
});

test('cannot double book the same court during an overlapping time window', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $court = Court::factory()->create();

    $tomorrow = now()->addDays(3)->format('Y-m-d');

    // Existing booking from 09:00 to 11:00
    Reservation::factory()->create([
        'user_id' => $user1->id,
        'court_id' => $court->id,
        'date' => $tomorrow,
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
        'status' => 'confirmed',
    ]);

    // Second user attempts to book 10:00 to 12:00 (overlaps with 09:00-11:00)
    $response = $this->actingAs($user2)->from(route('reservations.create'))->post(route('reservations.store'), [
        'court_id' => $court->id,
        'date' => $tomorrow,
        'start_time' => '10:00',
        'end_time' => '12:00',
        'duration_hours' => 2,
    ]);

    $response->assertSessionHasErrors(['start_time']);
    expect(Reservation::count())->toBe(1);
});

test('can book another court at the same time without conflict', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $court1 = Court::factory()->create();
    $court2 = Court::factory()->create();

    $tomorrow = now()->addDays(3)->format('Y-m-d');

    Reservation::factory()->create([
        'user_id' => $user1->id,
        'court_id' => $court1->id,
        'date' => $tomorrow,
        'start_time' => '14:00:00',
        'end_time' => '15:00:00',
        'status' => 'confirmed',
    ]);

    // Second booking on different court
    $response = $this->actingAs($user2)->post(route('reservations.store'), [
        'court_id' => $court2->id,
        'date' => $tomorrow,
        'start_time' => '14:00',
        'end_time' => '15:00',
        'duration_hours' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    expect(Reservation::count())->toBe(2);
});

test('cannot rent more equipment than available in inventory', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create();
    $paddle = Equipment::factory()->paddle()->create(['stock' => 2]);

    $tomorrow = now()->addDays(4)->format('Y-m-d');

    $response = $this->actingAs($user)->from(route('reservations.create'))->post(route('reservations.store'), [
        'court_id' => $court->id,
        'date' => $tomorrow,
        'start_time' => '08:00',
        'end_time' => '09:00',
        'duration_hours' => 1,
        'equipment' => [
            $paddle->id => 4, // Exceeds available stock of 2
        ],
    ]);

    $response->assertSessionHasErrors(['equipment']);
    expect(Reservation::count())->toBe(0);
    expect($paddle->fresh()->stock)->toBe(2);
});

test('user can cancel an upcoming reservation', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create();
    $paddle = Equipment::factory()->paddle()->create(['stock' => 5]);

    $futureDate = now()->addDays(5)->format('Y-m-d');

    $this->actingAs($user)->post(route('reservations.store'), [
        'court_id' => $court->id,
        'date' => $futureDate,
        'start_time' => '16:00',
        'end_time' => '17:00',
        'duration_hours' => 1,
        'equipment' => [
            $paddle->id => 2,
        ],
    ]);

    $reservation = Reservation::firstOrFail();
    expect($paddle->fresh()->stock)->toBe(3);

    $response = $this->actingAs($user)->post(route('reservations.cancel', $reservation));

    $response->assertSessionHas('status');
    expect($reservation->fresh()->status)->toBe('cancelled');
    expect($paddle->fresh()->stock)->toBe(5);
});

test('user cannot cancel someone elses reservation', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $court = Court::factory()->create();

    $reservation = Reservation::factory()->create([
        'user_id' => $owner->id,
        'court_id' => $court->id,
        'date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '10:00:00',
        'end_time' => '11:00:00',
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($otherUser)->post(route('reservations.cancel', $reservation));

    $response->assertStatus(403);
    expect($reservation->fresh()->status)->toBe('confirmed');
});

test('availability api endpoint returns booked slots for a given court and date', function () {
    $court = Court::factory()->create();
    $date = now()->addDays(2)->format('Y-m-d');

    Reservation::factory()->create([
        'court_id' => $court->id,
        'date' => $date,
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'status' => 'confirmed',
    ]);

    $response = $this->getJson(route('api.availability', [
        'court_id' => $court->id,
        'date' => $date,
    ]));

    $response->assertStatus(200)
        ->assertJson([
            'slots' => [
                ['start' => '09:00', 'end' => '10:00'],
            ],
        ]);
});
