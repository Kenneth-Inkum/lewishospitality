<?php

use App\Models\Setting;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::about')
        ->assertStatus(200);
});

it('retrieves origin story from settings', function () {
    Setting::set('about.origin_story', '<p>Test origin story content</p>');

    $component = Livewire::test('pages::about');
    
    expect($component->originStory)->toBe('<p>Test origin story content</p>');
});

it('retrieves philosophy from settings', function () {
    Setting::set('about.philosophy', '<p>Test philosophy content</p>');

    $component = Livewire::test('pages::about');
    
    expect($component->philosophy)->toBe('<p>Test philosophy content</p>');
});

it('retrieves show flags from settings with defaults', function () {
    Setting::set('about.show_team', true);
    Setting::set('about.show_awards', true);
    Setting::set('about.show_values', false);

    $component = Livewire::test('pages::about');
    
    expect($component->showTeam)->toBeTrue();
    expect($component->showAwards)->toBeTrue();
    expect($component->showValues)->toBeFalse();
});

it('returns default values when settings are not set', function () {
    $component = Livewire::test('pages::about');
    
    expect($component->showTeam)->toBeFalse();
    expect($component->showAwards)->toBeFalse();
    expect($component->showValues)->toBeTrue();
});

it('retrieves awards list from settings', function () {
    $awards = [
        ['title' => 'Best Restaurant', 'year' => '2024'],
        ['title' => 'Top Chef', 'year' => '2023'],
    ];
    
    Setting::set('about.awards_list', $awards);

    $component = Livewire::test('pages::about');
    
    expect($component->awards)->toBe($awards);
});

it('returns empty array when awards list is not set', function () {
    $component = Livewire::test('pages::about');
    
    expect($component->awards)->toBe([]);
});

// ─── Browser tests ────────────────────────────────────────────────────────────

it('loads the about page without javascript errors', function () {
    Setting::set('about.origin_story', '<p>Our story</p>');
    
    visit('/about')
        ->inDarkMode()
        ->assertNoSmoke();
});

it('renders the hero with brand eyebrow and headline', function () {
    visit('/about')
        ->inDarkMode()
        ->assertSee('Lewis Hospitality')
        ->assertSee('Our Story')
        ->assertSee('Two decades of bringing people together around exceptional food.')
        ->assertNoJavaScriptErrors();
});

it('displays origin story section when content exists', function () {
    Setting::set('about.origin_story', '<p>Founded in 2004, we started with a vision.</p>');

    visit('/about')
        ->inDarkMode()
        ->assertSee('How It Started')
        ->assertSee('Founded in 2004, we started with a vision.')
        ->assertNoJavaScriptErrors();
});

it('hides origin story section when content is null', function () {
    visit('/about')
        ->inDarkMode()
        ->assertDontSee('How It Started')
        ->assertNoJavaScriptErrors();
});

it('displays philosophy section when show_values is true and content exists', function () {
    Setting::set('about.show_values', true);
    Setting::set('about.philosophy', '<p>We believe in great ingredients.</p>');

    visit('/about')
        ->inDarkMode()
        ->assertSee('Our Philosophy')
        ->assertSee('We believe in great ingredients.')
        ->assertNoJavaScriptErrors();
});

it('hides philosophy section when show_values is false', function () {
    Setting::set('about.show_values', false);
    Setting::set('about.philosophy', '<p>Philosophy content</p>');

    visit('/about')
        ->inDarkMode()
        ->assertDontSee('Our Philosophy')
        ->assertNoJavaScriptErrors();
});

it('displays awards section with award cards when show_awards is true', function () {
    Setting::set('about.show_awards', true);
    Setting::set('about.awards_list', [
        [
            'title' => 'Best New Restaurant',
            'year' => '2005',
            'description' => 'Washington Post',
        ],
        [
            'title' => 'Michelin Star',
            'year' => '2024',
            'description' => 'Michelin Guide',
        ],
    ]);

    visit('/about')
        ->inDarkMode()
        ->assertSee('Recognition')
        ->assertSee('Best New Restaurant')
        ->assertSee('2005')
        ->assertSee('Washington Post')
        ->assertSee('Michelin Star')
        ->assertSee('2024')
        ->assertNoJavaScriptErrors();
});

it('shows empty state when awards section is enabled but no awards exist', function () {
    Setting::set('about.show_awards', true);
    Setting::set('about.awards_list', []);

    visit('/about')
        ->inDarkMode()
        ->assertSee('Recognition')
        ->assertSee('Awards and recognition will appear here.')
        ->assertNoJavaScriptErrors();
});

it('hides awards section when show_awards is false', function () {
    Setting::set('about.show_awards', false);

    visit('/about')
        ->inDarkMode()
        ->assertDontSee('Recognition')
        ->assertNoJavaScriptErrors();
});

it('displays team section when show_team is true', function () {
    Setting::set('about.show_team', true);

    visit('/about')
        ->inDarkMode()
        ->assertSee('Our Team')
        ->assertSee('Team member profiles will appear here once configured.')
        ->assertNoJavaScriptErrors();
});

it('hides team section when show_team is false', function () {
    Setting::set('about.show_team', false);

    visit('/about')
        ->inDarkMode()
        ->assertDontSee('Our Team')
        ->assertNoJavaScriptErrors();
});

it('renders correctly on a mobile viewport', function () {
    Setting::set('about.origin_story', '<p>Our story</p>');

    visit('/about')
        ->inDarkMode()
        ->on()->mobile()
        ->assertSee('Our Story')
        ->assertSee('How It Started')
        ->assertNoJavaScriptErrors();
});
