<?php

use App\Models\Event;

test('event has correct fillable attributes', function () {
    $event = new Event();

    expect($event->getFillable())->toContain('user_id')
        ->toContain('title')
        ->toContain('description')
        ->toContain('date')
        ->toContain('location')
        ->toContain('max_participants')
        ->toContain('status')
        ->toContain('price')
        ->toContain('currency')
        ->toContain('banner');
});

test('event has user relationship method', function () {
    $event = new Event();

    expect(method_exists($event, 'user'))->toBeTrue();
});

test('event has participants relationship method', function () {
    $event = new Event();

    expect(method_exists($event, 'participants'))->toBeTrue();
});
