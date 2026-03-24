<?php

use App\Models\Event;
use App\Models\HappyHour;
use App\Models\Location;
use App\Models\Promotion;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::events')
        ->assertStatus(200);
});

it('defaults to all filter on mount', function () {
    Livewire::test('pages::events')
        ->assertSet('filter', 'all');
});

it('filters events by type', function () {
    Livewire::test('pages::events')
        ->set('filter', 'events')
        ->assertSet('filter', 'events')
        ->set('filter', 'seasonal')
        ->assertSet('filter', 'seasonal')
        ->set('filter', 'happy_hours')
        ->assertSet('filter', 'happy_hours');
});

it('shows only published upcoming events', function () {
    Event::factory()->published()->create(['starts_at' => now()->addDays(5)]);
    Event::factory()->create(['published' => false, 'starts_at' => now()->addDays(3)]);
    Event::factory()->published()->create(['starts_at' => now()->subDays(2)]);

    $component = Livewire::test('pages::events');
    
    expect($component->events)->toHaveCount(1);
});

it('filters seasonal events by ends_at presence', function () {
    Event::factory()->published()->create([
        'starts_at' => now()->addDays(5),
        'ends_at' => now()->addDays(10),
    ]);
    Event::factory()->published()->create([
        'starts_at' => now()->addDays(3),
        'ends_at' => null,
    ]);

    $component = Livewire::test('pages::events')
        ->set('filter', 'seasonal');
    
    expect($component->events)->toHaveCount(1);
});

it('shows active promotions marked for homepage', function () {
    Promotion::factory()->create([
        'active' => true,
        'show_on_homepage' => true,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(5),
    ]);
    Promotion::factory()->create([
        'active' => true,
        'show_on_homepage' => false,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(5),
    ]);

    $component = Livewire::test('pages::events');
    
    expect($component->promotions)->toHaveCount(1);
});

it('shows happy hours for current day only', function () {
    $location = Location::factory()->create();
    $todayDayOfWeek = (int) now()->format('w');
    $tomorrowDayOfWeek = ($todayDayOfWeek + 1) % 7;

    HappyHour::factory()->create([
        'location_id' => $location->id,
        'day_of_week' => $todayDayOfWeek,
        'active' => true,
    ]);
    HappyHour::factory()->create([
        'location_id' => $location->id,
        'day_of_week' => $tomorrowDayOfWeek,
        'active' => true,
    ]);

    $component = Livewire::test('pages::events');
    
    expect($component->happyHours)->toHaveCount(1);
});

// ─── Browser tests ────────────────────────────────────────────────────────────

it('loads the events page without javascript errors', function () {
    visit('/events')
        ->inDarkMode()
        ->assertNoSmoke();
});

it('renders the hero with brand eyebrow and headline', function () {
    visit('/events')
        ->inDarkMode()
        ->assertSee('Lewis Hospitality')
        ->assertSee('Events & Happenings')
        ->assertSee('Join us for special occasions, happy hours, and seasonal celebrations.')
        ->assertNoJavaScriptErrors();
});

it('displays filter tabs for all, events, happy hours, and seasonal', function () {
    visit('/events')
        ->inDarkMode()
        ->assertSee('All')
        ->assertSee('Events')
        ->assertSee('Happy Hours')
        ->assertSee('Seasonal')
        ->assertNoJavaScriptErrors();
});

it('switches filter when tab is clicked', function () {
    Event::factory()->published()->create(['starts_at' => now()->addDays(5)]);

    visit('/events')
        ->inDarkMode()
        ->click('Events')
        ->assertNoJavaScriptErrors()
        ->click('Seasonal')
        ->assertNoJavaScriptErrors();
});

it('shows promotions bar when active promotions exist', function () {
    Promotion::factory()->create([
        'title' => 'Summer Special',
        'active' => true,
        'show_on_homepage' => true,
        'starts_at' => now()->subDays(1),
        'ends_at' => now()->addDays(5),
    ]);

    visit('/events')
        ->inDarkMode()
        ->assertSee('Special Offers')
        ->assertSee('Summer Special')
        ->assertNoJavaScriptErrors();
});

it('displays happy hours section with location and time', function () {
    $location = Location::factory()->create(['name' => 'Penn Quarter']);
    $todayDayOfWeek = (int) now()->format('w');

    HappyHour::factory()->create([
        'location_id' => $location->id,
        'day_of_week' => $todayDayOfWeek,
        'starts_at' => '16:00:00',
        'ends_at' => '19:00:00',
        'label' => 'Happy Hour',
        'active' => true,
    ]);

    visit('/events')
        ->inDarkMode()
        ->assertSee("Today's Happy Hours")
        ->assertSee('Penn Quarter')
        ->assertSee('4:00 PM')
        ->assertSee('7:00 PM')
        ->assertNoJavaScriptErrors();
});

it('displays event cards with title, date, and description', function () {
    Event::factory()->published()->create([
        'title' => 'Wine Tasting Night',
        'description' => 'Join us for an exclusive wine tasting experience.',
        'starts_at' => now()->addDays(5),
    ]);

    visit('/events')
        ->inDarkMode()
        ->assertSee('Wine Tasting Night')
        ->assertSee('Join us for an exclusive wine tasting experience.')
        ->assertNoJavaScriptErrors();
});

it('shows empty state when no events exist', function () {
    visit('/events')
        ->inDarkMode()
        ->assertSee('No events found')
        ->assertSee('Check back soon for upcoming events and special occasions.')
        ->assertNoJavaScriptErrors();
});

it('navigates to event detail page when event card is clicked', function () {
    $event = Event::factory()->published()->create([
        'title' => 'Wine Tasting Night',
        'slug' => 'wine-tasting-night',
        'starts_at' => now()->addDays(5),
    ]);

    visit('/events')
        ->inDarkMode()
        ->click('Wine Tasting Night')
        ->assertUrlIs('/events/wine-tasting-night')
        ->assertNoJavaScriptErrors();
});

it('renders correctly on a mobile viewport', function () {
    Event::factory()->published()->create(['starts_at' => now()->addDays(5)]);

    visit('/events')
        ->inDarkMode()
        ->on()->mobile()
        ->assertSee('Events & Happenings')
        ->assertSee('All')
        ->assertNoJavaScriptErrors();
});
