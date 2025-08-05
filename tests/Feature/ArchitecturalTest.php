<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

test('globals')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->not->toBeUsed();

test('traits')
    ->expect('App\Models')
    ->toBeClasses();

test('controllers')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller')
    ->toBeClasses()
    ->classes->not->toBeFinal();

// php artisan test --coverage --filter=ArchitecturalTest
