<?php

use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\Location;
use Livewire\Livewire;

it('loads event by slug and shows 404 for unpublished events', function () {
    $published = Event::factory()->published()->create(['slug' => 'wine-night']);
    $unpublished = Event::factory()->create(['published' => false, 'slug' => 'secret-event']);

    Livewire::test('pages::event-detail', ['slug' => 'wine-night'])
        ->assertStatus(200);

    expect(fn () => Livewire::test('pages::event-detail', ['slug' => 'secret-event']))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

it('loads event by slug and shows 404 for non-existent events', function () {
    expect(fn () => Livewire::test('pages::event-detail', ['slug' => 'non-existent']))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

it('submits rsvp and creates event rsvp record', function () {
    $event = Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => true,
    ]);

    Livewire::test('pages::event-detail', ['slug' => 'wine-night'])
        ->set('rsvpName', 'Jane Doe')
        ->set('rsvpEmail', 'jane@example.com')
        ->set('rsvpPartySize', 4)
        ->call('submitRsvp')
        ->assertSet('rsvpSubmitted', true);

    expect(EventRsvp::count())->toBe(1);

    $rsvp = EventRsvp::first();
    expect($rsvp->event_id)->toBe($event->id);
    expect($rsvp->name)->toBe('Jane Doe');
    expect($rsvp->email)->toBe('jane@example.com');
    expect($rsvp->party_size)->toBe(4);
});

it('resets rsvp form fields after successful submission', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => true,
    ]);

    Livewire::test('pages::event-detail', ['slug' => 'wine-night'])
        ->set('rsvpName', 'Jane Doe')
        ->set('rsvpEmail', 'jane@example.com')
        ->set('rsvpPartySize', 4)
        ->call('submitRsvp')
        ->assertSet('rsvpName', '')
        ->assertSet('rsvpEmail', '')
        ->assertSet('rsvpPartySize', 1);
});

it('validates rsvp required fields', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => true,
    ]);

    Livewire::test('pages::event-detail', ['slug' => 'wine-night'])
        ->call('submitRsvp')
        ->assertHasErrors(['rsvpName', 'rsvpEmail', 'rsvpPartySize']);
});

it('validates rsvp email format', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => true,
    ]);

    Livewire::test('pages::event-detail', ['slug' => 'wine-night'])
        ->set('rsvpName', 'Jane Doe')
        ->set('rsvpEmail', 'not-an-email')
        ->set('rsvpPartySize', 2)
        ->call('submitRsvp')
        ->assertHasErrors(['rsvpEmail']);
});

it('validates rsvp party size range', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => true,
    ]);

    Livewire::test('pages::event-detail', ['slug' => 'wine-night'])
        ->set('rsvpName', 'Jane Doe')
        ->set('rsvpEmail', 'jane@example.com')
        ->set('rsvpPartySize', 0)
        ->call('submitRsvp')
        ->assertHasErrors(['rsvpPartySize']);

    Livewire::test('pages::event-detail', ['slug' => 'wine-night'])
        ->set('rsvpName', 'Jane Doe')
        ->set('rsvpEmail', 'jane@example.com')
        ->set('rsvpPartySize', 25)
        ->call('submitRsvp')
        ->assertHasErrors(['rsvpPartySize']);
});

it('does not submit rsvp when rsvp is disabled', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => false,
    ]);

    Livewire::test('pages::event-detail', ['slug' => 'wine-night'])
        ->set('rsvpName', 'Jane Doe')
        ->set('rsvpEmail', 'jane@example.com')
        ->set('rsvpPartySize', 4)
        ->call('submitRsvp')
        ->assertSet('rsvpSubmitted', false);

    expect(EventRsvp::count())->toBe(0);
});

// ─── Browser tests ────────────────────────────────────────────────────────────

it('loads the event detail page without javascript errors', function () {
    Event::factory()->published()->create(['slug' => 'wine-night']);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertNoSmoke();
});

it('renders the hero with event title and date', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'title' => 'Wine Tasting Night',
        'starts_at' => now()->addDays(5)->setTime(19, 0),
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertSee('Wine Tasting Night')
        ->assertSee(now()->addDays(5)->format('F j, Y'))
        ->assertNoJavaScriptErrors();
});

it('displays breadcrumb navigation back to events', function () {
    Event::factory()->published()->create(['slug' => 'wine-night']);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertSee('Events')
        ->assertSee('Back to all events')
        ->assertNoJavaScriptErrors();
});

it('shows event description in prose format', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'description' => '<p>Join us for an exclusive wine tasting experience featuring rare vintages.</p>',
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertSee('Join us for an exclusive wine tasting experience featuring rare vintages.')
        ->assertNoJavaScriptErrors();
});

it('displays location information when event has location', function () {
    $location = Location::factory()->create([
        'name' => 'Penn Quarter',
        'address' => '701 Pennsylvania Ave NW',
    ]);

    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'location_id' => $location->id,
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertSee('Penn Quarter')
        ->assertSee('701 Pennsylvania Ave NW')
        ->assertNoJavaScriptErrors();
});

it('shows rsvp form when rsvp is enabled', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => true,
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertSee('RSVP')
        ->assertSee('Reserve your spot for this event.')
        ->assertSee('Name')
        ->assertSee('Email')
        ->assertSee('Party Size')
        ->assertSee('Confirm RSVP')
        ->assertNoJavaScriptErrors();
});

it('hides rsvp form when rsvp is disabled', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => false,
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertDontSee('RSVP')
        ->assertDontSee('Confirm RSVP')
        ->assertNoJavaScriptErrors();
});

it('submits rsvp form and shows success message', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'rsvp_enabled' => true,
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->type('[wire\:model="rsvpName"]', 'Jane Doe')
        ->type('[wire\:model="rsvpEmail"]', 'jane@example.com')
        ->type('[wire\:model="rsvpPartySize"]', '4')
        ->press('Confirm RSVP')
        ->assertSee("You're on the list!")
        ->assertSee("We've sent a confirmation to your email.")
        ->assertNoJavaScriptErrors();
});

it('displays event details sidebar with date and time', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'starts_at' => now()->addDays(5)->setTime(19, 0),
        'ends_at' => now()->addDays(5)->setTime(22, 0),
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertSee('Event Details')
        ->assertSee('Date')
        ->assertSee('Time')
        ->assertSee('Ends')
        ->assertNoJavaScriptErrors();
});

it('shows external link cta when configured', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'cta_type' => 'external_link',
        'cta_label' => 'Buy Tickets',
        'cta_url' => 'https://tickets.example.com',
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->assertSee('Buy Tickets')
        ->assertNoJavaScriptErrors();
});

it('navigates back to events list when back link is clicked', function () {
    Event::factory()->published()->create(['slug' => 'wine-night']);

    visit('/events/wine-night')
        ->inDarkMode()
        ->click('Back to all events')
        ->assertUrlIs('/events')
        ->assertNoJavaScriptErrors();
});

it('renders correctly on a mobile viewport', function () {
    Event::factory()->published()->create([
        'slug' => 'wine-night',
        'title' => 'Wine Tasting Night',
        'rsvp_enabled' => true,
    ]);

    visit('/events/wine-night')
        ->inDarkMode()
        ->on()->mobile()
        ->assertSee('Wine Tasting Night')
        ->assertSee('RSVP')
        ->assertNoJavaScriptErrors();
});
