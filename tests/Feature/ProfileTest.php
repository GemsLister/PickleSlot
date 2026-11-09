<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('players can view their profile', function () {
    $player = User::factory()->create();

    $response = $this->actingAs($player)->get(route('profile.edit'));

    $response->assertOk()
        ->assertSee('My Profile')
        ->assertSee('Personal information')
        ->assertSee('Change password');
});

test('administrators can view their profile', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('profile.edit'));

    $response->assertOk()
        ->assertSee('Administrator')
        ->assertSee('My Profile');
});

test('guests are redirected to login when viewing a profile', function () {
    $response = $this->get(route('profile.edit'));

    $response->assertRedirect(route('login'));
});

test('users can update their profile without changing their role', function () {
    $player = User::factory()->create([
        'name' => 'Original Player',
        'email' => 'original@example.com',
    ]);

    $response = $this->actingAs($player)->patch(route('profile.update'), [
        'name' => 'Updated Player',
        'email' => 'updated@example.com',
        'phone' => '+15551234567',
        'is_admin' => true,
    ]);

    $response->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status', 'Your profile has been updated.');

    $this->assertDatabaseHas('users', [
        'id' => $player->id,
        'name' => 'Updated Player',
        'email' => 'updated@example.com',
        'phone' => '+15551234567',
        'is_admin' => false,
    ]);
});

test('users can save their profile with their existing email', function () {
    $player = User::factory()->create([
        'email' => 'player@example.com',
    ]);

    $response = $this->actingAs($player)->patch(route('profile.update'), [
        'name' => 'Updated Player',
        'email' => $player->email,
        'phone' => $player->phone,
    ]);

    $response->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status');

    expect($player->fresh()->email)->toBe('player@example.com');
});

test('users cannot update their profile with another users email', function () {
    $player = User::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($player)->patch(route('profile.update'), [
        'name' => 'Updated Player',
        'email' => $otherUser->email,
        'phone' => $player->phone,
    ]);

    $response->assertSessionHasErrors('email');

    expect($player->fresh()->name)->toBe($player->name);
});

test('profile pages escape user-provided names', function () {
    $player = User::factory()->create([
        'name' => '<script>alert("xss")</script>',
    ]);

    $response = $this->actingAs($player)->get(route('profile.edit'));

    $response->assertOk()
        ->assertSee('<script>alert("xss")</script>')
        ->assertDontSee('<script>alert("xss")</script>', false);
});

test('users can change their password with their current password', function () {
    $player = User::factory()->create([
        'password' => 'current-password',
    ]);

    $response = $this->actingAs($player)->put(route('profile.password.update'), [
        'current_password' => 'current-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status', 'Your password has been updated.');

    expect(Hash::check('new-password', $player->fresh()->password))->toBeTrue();
});

test('users cannot change their password with an incorrect current password', function () {
    $player = User::factory()->create([
        'password' => 'current-password',
    ]);
    $originalPasswordHash = $player->password;

    $response = $this->actingAs($player)->put(route('profile.password.update'), [
        'current_password' => 'incorrect-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasErrors('current_password');

    expect($player->fresh()->password)->toBe($originalPasswordHash);
});
