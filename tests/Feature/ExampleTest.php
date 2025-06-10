<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('redirects to login when not authenticated', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('redirects to dashboard when authenticated', function () {
    $user = User::factory()->create(['role' => 'client']);

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(302);
    $response->assertRedirect(route('dashboard.client'));
});
