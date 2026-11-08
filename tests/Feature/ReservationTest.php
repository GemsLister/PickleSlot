<?php

use App\Models\Court;
use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\User;

test('reservation create page is accessible by authenticated users', function () {
    $user = User::factory()->create();
    Court::factory()->create();

    $response = $this->actingAs($user)->get(route('reservations.create'));

    $response->assertStatus(200);
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
        'notes' => 'Please have paddles ready near court entrance.',
        'equipment' => [
            $paddle->id => 2,
            $balls->id => 1,
        ],
    ]);

    $reservation = Reservation::first();
    expect($reservation)->not->toBeNull();

    $response->assertRedirect(route('reservations.show', $reservation));

    expect((float) $reservation->court_rate)->toBe(30.00);
    expect((float) $reservation->court_total)->toBe(30.00);
    expect((float) $reservation->equipment_total)->toBe(15.50); // (2 * 6.00) + (1 * 3.50) = 15.50
    expect((float) $reservation->total_price)->toBe(45.50); // 30.00 + 15.50 = 45.50
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
        'quantity' => 1,
        'total_price' => 3.50,
    ]);
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
        'equipment' => [
            $paddle->id => 4, // Exceeds available stock of 2
        ],
    ]);

    $response->assertSessionHasErrors(['equipment']);
    expect(Reservation::count())->toBe(0);
});

test('user can cancel an upcoming reservation', function () {
    $user = User::factory()->create();
    $court = Court::factory()->create();

    $futureDate = now()->addDays(5)->format('Y-m-d');

    $reservation = Reservation::factory()->create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'date' => $futureDate,
        'start_time' => '16:00:00',
        'end_time' => '17:00:00',
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($user)->post(route('reservations.cancel', $reservation));

    $response->assertSessionHas('status');
    expect($reservation->fresh()->status)->toBe('cancelled');
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
