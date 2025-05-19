<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('event has correct fillable attributes', function () {
    $event = new Event();

    expect($event->getFillable())->toContain('user_id')
        ->toContain('title')
        ->toContain('banner')
        ->toContain('description')
        ->toContain('date')
        ->toContain('location')
        ->toContain('status')
        ->toContain('max_participants')
        ->toContain('price')
        ->toContain('currency');
});
