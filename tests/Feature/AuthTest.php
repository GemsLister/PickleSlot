<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register and be authenticated', function () {
    $response = $this->post('/register', [
        'name' => 'Jordan Player',
        'email' => 'jordan@example.com',
        'phone' => '+15551234567',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('reservations.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'jordan@example.com',
        'is_admin' => false,
    ]);
});

test('registration requires confirmed password and unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->post('/register', [
        'name' => 'Duplicate User',
        'email' => 'existing@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'mismatch123',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
});

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'email' => 'player@example.com',
        'password' => Hash::make('secretpass'),
    ]);

    $response = $this->post('/login', [
        'email' => 'player@example.com',
        'password' => 'secretpass',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('reservations.index'));
});

test('admin login redirects to admin dashboard', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secretpass'),
    ]);

    $response = $this->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'secretpass',
    ]);

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect(route('admin.dashboard'));
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('users can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
