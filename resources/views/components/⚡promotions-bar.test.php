<?php

use App\Models\Promotion;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('promotions-bar')
        ->assertStatus(200);
});

it('shows only active promotions marked for homepage', function () {
    Promotion::factory()->create([
        'title' => 'Active Homepage Promo',
        'active' => true,
        'show_on_homepage' => true,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(5),
    ]);
    
    Promotion::factory()->create([
        'title' => 'Inactive Promo',
        'active' => false,
        'show_on_homepage' => true,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(5),
    ]);
    
    Promotion::factory()->create([
        'title' => 'Not Homepage Promo',
        'active' => true,
        'show_on_homepage' => false,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(5),
    ]);

    $component = Livewire::test('promotions-bar');
    
    expect($component->promotions)->toHaveCount(1);
    expect($component->promotions->first()->title)->toBe('Active Homepage Promo');
});

it('limits promotions to maximum of 3', function () {
    Promotion::factory()->count(5)->create([
        'active' => true,
        'show_on_homepage' => true,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(5),
    ]);

    $component = Livewire::test('promotions-bar');
    
    expect($component->promotions)->toHaveCount(3);
});

it('returns empty collection when no promotions exist', function () {
    $component = Livewire::test('promotions-bar');
    
    expect($component->promotions)->toBeEmpty();
});

it('hides component when no promotions are available', function () {
    $html = Livewire::test('promotions-bar')->html();
    
    expect($html)->not->toContain('Special Offers');
});

it('displays component when promotions are available', function () {
    Promotion::factory()->create([
        'title' => 'Summer Special',
        'active' => true,
        'show_on_homepage' => true,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(5),
    ]);

    $html = Livewire::test('promotions-bar')->html();
    
    expect($html)->toContain('Special Offers');
    expect($html)->toContain('Summer Special');
});

it('displays promotion end date when available', function () {
    Promotion::factory()->create([
        'title' => 'Limited Offer',
        'active' => true,
        'show_on_homepage' => true,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(2),
    ]);

    $html = Livewire::test('promotions-bar')->html();
    
    expect($html)->toContain('Ends');
});

it('does not display end date when promotion has no end date', function () {
    Promotion::factory()->create([
        'title' => 'Ongoing Offer',
        'active' => true,
        'show_on_homepage' => true,
        'starts_at' => now()->subDays(1),
        'ends_at' => null,
    ]);

    $html = Livewire::test('promotions-bar')->html();
    
    expect($html)->not->toContain('Ends');
});
