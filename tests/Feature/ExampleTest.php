<?php

use App\Models\User;

it('redirects to login when not authenticated', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

it('redirects to appropriate dashboard when authenticated', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/');

    $response->assertStatus(302);
    $response->assertRedirect(route('dashboard.admin'));
});
