<?php

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user has correct fillable attributes', function () {
    $user = new User();

    expect($user->getFillable())->toContain('name')
        ->toContain('email')
        ->toContain('password')
        ->toContain('role');
});

test('user has correct hidden attributes', function () {
    $user = new User();

    expect($user->getHidden())->toContain('password')
        ->toContain('remember_token');
});

test('user has correct casts', function () {
    $user = new User();

    // Accéder directement aux propriétés protégées pour éviter d'appeler la méthode casts()
    $casts = (new \ReflectionClass($user))->getMethod('casts')->invoke($user);

    expect($casts)->toHaveKey('email_verified_at')
        ->toHaveKey('password');

    expect($casts['email_verified_at'])->toBe('datetime');
    expect($casts['password'])->toBe('hashed');
});
