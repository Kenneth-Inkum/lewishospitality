<?php

use App\Models\ContactSubmission;
use App\Models\Location;
use App\Models\LocationHour;
use Livewire\Livewire;

it('renders successfully', function () {
    Location::factory()->primary()->create();

    Livewire::test('pages::contact')->assertStatus(200);
});

it('pre-selects the primary location on mount', function () {
    $primary = Location::factory()->primary()->create();
    Location::factory()->create();

    Livewire::test('pages::contact')
        ->assertSet('locationId', $primary->id);
});

it('pre-selects the first active location when no primary exists', function () {
    $first = Location::factory()->create(['is_primary' => false]);
    Location::factory()->create(['is_primary' => false]);

    Livewire::test('pages::contact')
        ->assertSet('locationId', $first->id);
});

it('updates the selected location reactively', function () {
    $locationA = Location::factory()->primary()->create();
    $locationB = Location::factory()->create();

    Livewire::test('pages::contact')
        ->assertSet('locationId', $locationA->id)
        ->set('locationId', $locationB->id)
        ->assertSet('locationId', $locationB->id);
});

it('submits the contact form and creates a contact submission', function () {
    $location = Location::factory()->primary()->create();

    Livewire::test('pages::contact')
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('subject', 'Reservation Inquiry')
        ->set('message', 'I would like to make a reservation for next Friday.')
        ->call('submit')
        ->assertSet('submitted', true);

    expect(ContactSubmission::count())->toBe(1);

    $submission = ContactSubmission::first();
    expect($submission->name)->toBe('Jane Doe');
    expect($submission->email)->toBe('jane@example.com');
    expect($submission->subject)->toBe('Reservation Inquiry');
    expect($submission->location_id)->toBe($location->id);
});

it('resets form fields after successful submission', function () {
    Location::factory()->primary()->create();

    Livewire::test('pages::contact')
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('subject', 'Hello')
        ->set('message', 'A test message')
        ->call('submit')
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('subject', '')
        ->assertSet('message', '');
});

it('validates that required fields are present', function () {
    Location::factory()->primary()->create();

    Livewire::test('pages::contact')
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'subject', 'message']);
});

it('validates email format', function () {
    Location::factory()->primary()->create();

    Livewire::test('pages::contact')
        ->set('name', 'Jane Doe')
        ->set('email', 'not-an-email')
        ->set('subject', 'Hello')
        ->set('message', 'Test message')
        ->call('submit')
        ->assertHasErrors(['email']);
});

it('validates that location must exist when provided', function () {
    Location::factory()->primary()->create();

    Livewire::test('pages::contact')
        ->set('locationId', 99999)
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('subject', 'Hello')
        ->set('message', 'Test message')
        ->call('submit')
        ->assertHasErrors(['locationId']);
});

it('accepts a null location id', function () {
    Location::factory()->create();

    Livewire::test('pages::contact')
        ->set('locationId', null)
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('subject', 'Hello')
        ->set('message', 'Test message')
        ->call('submit')
        ->assertSet('submitted', true)
        ->assertHasNoErrors();

    expect(ContactSubmission::first()->location_id)->toBeNull();
});

// ─── Browser tests ────────────────────────────────────────────────────────────

it('loads the contact page without javascript errors', function () {
    Location::factory()->primary()->create();

    visit('/contact')
        ->inDarkMode()
        ->assertNoSmoke();
});

it('renders the hero with brand eyebrow, headline and subheading', function () {
    Location::factory()->primary()->create();

    visit('/contact')
        ->inDarkMode()
        ->assertSee('Lewis Hospitality')
        ->assertSee("Let's Connect")
        ->assertSee("We're always happy to hear from our guests.")
        ->assertNoJavaScriptErrors();
});

it('displays the sticky nav wordmark and reserve a table cta', function () {
    Location::factory()->primary()->create();

    visit('/contact')
        ->inDarkMode()
        ->assertSee('Lewis')
        ->assertSee('Reserve a Table')
        ->assertSee('Contact')
        ->assertNoJavaScriptErrors();
});

it('renders the footer with brand name and both location columns', function () {
    Location::factory()->primary()->create();

    visit('/contact')
        ->inDarkMode()
        ->assertSee('Lewis Hospitality Group')
        ->assertSee('Penn Quarter')
        ->assertSee('Bethesda')
        ->assertSee('Explore')
        ->assertNoJavaScriptErrors();
});

it('shows location tab pills in the hero when multiple locations exist', function () {
    Location::factory()->primary()->create(['name' => 'Penn Quarter']);
    Location::factory()->create(['name' => 'Bethesda']);

    visit('/contact')
        ->inDarkMode()
        ->assertSee('Penn Quarter')
        ->assertSee('Bethesda')
        ->assertNoJavaScriptErrors();
});

it('switches the location info panel when a tab pill is clicked', function () {
    Location::factory()->primary()->create([
        'name' => 'Penn Quarter',
        'address' => '701 Pennsylvania Ave NW',
        'city' => 'Washington',
        'state' => 'DC',
        'zip' => '20004',
    ]);

    Location::factory()->create([
        'name' => 'Bethesda',
        'address' => '4922 Hampden Ln',
        'city' => 'Bethesda',
        'state' => 'MD',
        'zip' => '20814',
    ]);

    visit('/contact')
        ->inDarkMode()
        ->assertSee('701 Pennsylvania Ave NW')
        ->click('Bethesda')
        ->assertSee('4922 Hampden Ln')
        ->assertNoJavaScriptErrors();
});

it('displays the selected location address, phone and email', function () {
    Location::factory()->primary()->create([
        'name' => 'Penn Quarter',
        'address' => '701 Pennsylvania Ave NW',
        'city' => 'Washington',
        'state' => 'DC',
        'zip' => '20004',
        'phone' => '(202) 555-0199',
        'email' => 'info@lewishospitality.test',
    ]);

    visit('/contact')
        ->inDarkMode()
        ->assertSee('Penn Quarter')
        ->assertSee('701 Pennsylvania Ave NW')
        ->assertSee('Washington, DC 20004')
        ->assertSee('(202) 555-0199')
        ->assertSee('info@lewishospitality.test')
        ->assertNoJavaScriptErrors();
});

it('shows hours of operation with today highlighted', function () {
    $location = Location::factory()->primary()->create();

    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    foreach (range(0, 6) as $dayOfWeek) {
        LocationHour::factory()->create([
            'location_id' => $location->id,
            'day_of_week' => $dayOfWeek,
            'opens_at' => '11:00:00',
            'closes_at' => '22:00:00',
            'closed' => false,
        ]);
    }

    visit('/contact')
        ->inDarkMode()
        ->assertSee('Hours of Operation')
        ->assertSee($days[(int) now()->format('w')])
        ->assertSee('Today')
        ->assertNoJavaScriptErrors();
});

it('shows Closed for days marked as closed', function () {
    $location = Location::factory()->primary()->create();

    LocationHour::factory()->closed()->create([
        'location_id' => $location->id,
        'day_of_week' => 0,
    ]);

    visit('/contact')
        ->inDarkMode()
        ->assertSee('Closed')
        ->assertNoJavaScriptErrors();
});

it('renders the form card with all field labels and submit button', function () {
    Location::factory()->primary()->create();

    visit('/contact')
        ->inDarkMode()
        ->assertSee('Send Us a Message')
        ->assertSee("We'll get back to you within one business day.")
        ->assertSee('Name')
        ->assertSee('Email')
        ->assertSee('Subject')
        ->assertSee('Message')
        ->assertSee('Send Message')
        ->assertNoJavaScriptErrors();
});

it('shows a personalised success card after form submission', function () {
    Location::factory()->primary()->create();

    visit('/contact')
        ->inDarkMode()
        ->type('[wire\:model="name"]', 'Jane Doe')
        ->type('[wire\:model="email"]', 'jane@example.com')
        ->type('[wire\:model="subject"]', 'Reservation Inquiry')
        ->type('[wire\:model="message"]', 'I would like to make a reservation for next Friday.')
        ->press('Send Message')
        ->assertSee('Thank you, Jane Doe!')
        ->assertSee("We've received your message")
        ->assertSee('Send another message')
        ->assertNoJavaScriptErrors();
});

it('returns to the contact form when send another message is clicked', function () {
    Location::factory()->primary()->create();

    visit('/contact')
        ->inDarkMode()
        ->type('[wire\:model="name"]', 'Jane Doe')
        ->type('[wire\:model="email"]', 'jane@example.com')
        ->type('[wire\:model="subject"]', 'Test Subject')
        ->type('[wire\:model="message"]', 'A test message body.')
        ->press('Send Message')
        ->assertSee('Thank you, Jane Doe!')
        ->click('Send another message')
        ->assertSee('Send Us a Message')
        ->assertNoJavaScriptErrors();
});

it('renders correctly on a mobile viewport', function () {
    Location::factory()->primary()->create(['name' => 'Penn Quarter']);

    visit('/contact')
        ->inDarkMode()
        ->on()->mobile()
        ->assertSee("Let's Connect")
        ->assertSee('Send Us a Message')
        ->assertSee('Lewis Hospitality Group')
        ->assertNoJavaScriptErrors();
});
