<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

test('email can be verified with valid signature', function () {
    $user = User::factory()->unverified()->create();
    
    Event::fake();
    
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );
    
    $response = $this->actingAs($user)->get($verificationUrl);
    
    Event::assertDispatched(Verified::class);
    
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard').'?verified=1');
});

test('email verification fails with invalid signature', function () {
    $user = User::factory()->unverified()->create();
    
    Event::fake();
    
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );
    
    $response = $this->actingAs($user)->get($verificationUrl);
    
    Event::assertNotDispatched(Verified::class);
    
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
    $response->assertStatus(403);
});

test('already verified user is redirected', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    
    Event::fake();
    
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );
    
    $response = $this->actingAs($user)->get($verificationUrl);
    
    Event::assertNotDispatched(Verified::class);
    
    $response->assertRedirect(route('dashboard').'?verified=1');
});
