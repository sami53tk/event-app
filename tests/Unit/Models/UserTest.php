<?php

use App\Models\User;

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

    expect($user->getCasts())->toHaveKey('email_verified_at')
        ->toHaveKey('password');
});

test('user has events relationship method', function () {
    $user = new User();

    expect(method_exists($user, 'events'))->toBeTrue();
});

test('user has participated events relationship method', function () {
    $user = new User();

    expect(method_exists($user, 'participatedEvents'))->toBeTrue();
});
