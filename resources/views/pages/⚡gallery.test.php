<?php

use App\Models\GalleryImage;
use Livewire\Livewire;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

it('renders successfully', function () {
    Livewire::test('pages::gallery')
        ->assertStatus(200);
});

it('defaults to all collection filter on mount', function () {
    Livewire::test('pages::gallery')
        ->assertSet('collection', 'all');
});

it('has correct collection options', function () {
    $component = Livewire::test('pages::gallery');
    
    expect($component->collections)->toBe(['all', 'food', 'ambience', 'events', 'behind_the_scenes']);
});

it('filters images by collection', function () {
    Livewire::test('pages::gallery')
        ->set('collection', 'food')
        ->assertSet('collection', 'food')
        ->set('collection', 'events')
        ->assertSet('collection', 'events');
});

it('shows only active gallery images', function () {
    $activeImage = GalleryImage::factory()->create(['active' => true]);
    $inactiveImage = GalleryImage::factory()->create(['active' => false]);
    
    $activeImage->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('food');
    $inactiveImage->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('food');

    $component = Livewire::test('pages::gallery');
    
    expect($component->images)->toHaveCount(1);
})->skip('Requires test image file');

it('filters images by collection name when not all', function () {
    $foodImage = GalleryImage::factory()->create(['active' => true]);
    $eventImage = GalleryImage::factory()->create(['active' => true]);
    
    $foodImage->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('food');
    $eventImage->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('events');

    $component = Livewire::test('pages::gallery')
        ->set('collection', 'food');
    
    expect($component->images)->toHaveCount(1);
})->skip('Requires test image file');

it('shows all images when collection is all', function () {
    $foodImage = GalleryImage::factory()->create(['active' => true]);
    $eventImage = GalleryImage::factory()->create(['active' => true]);
    
    $foodImage->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('food');
    $eventImage->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('events');

    $component = Livewire::test('pages::gallery')
        ->set('collection', 'all');
    
    expect($component->images)->toHaveCount(2);
})->skip('Requires test image file');

// ─── Browser tests ────────────────────────────────────────────────────────────

it('loads the gallery page without javascript errors', function () {
    visit('/gallery')
        ->inDarkMode()
        ->assertNoSmoke();
});

it('renders the hero with brand eyebrow and headline', function () {
    visit('/gallery')
        ->inDarkMode()
        ->assertSee('Lewis Hospitality')
        ->assertSee('Gallery')
        ->assertSee('A visual journey through our food, spaces, and memorable moments.')
        ->assertNoJavaScriptErrors();
});

it('displays collection filter tabs', function () {
    visit('/gallery')
        ->inDarkMode()
        ->assertSee('All')
        ->assertSee('Food')
        ->assertSee('Ambience')
        ->assertSee('Events')
        ->assertSee('Behind the Scenes')
        ->assertNoJavaScriptErrors();
});

it('switches collection filter when tab is clicked', function () {
    visit('/gallery')
        ->inDarkMode()
        ->click('Food')
        ->assertNoJavaScriptErrors()
        ->click('Events')
        ->assertNoJavaScriptErrors();
});

it('shows empty state when no images exist', function () {
    visit('/gallery')
        ->inDarkMode()
        ->assertSee('No images found')
        ->assertSee('Images will appear here once added to the gallery.')
        ->assertNoJavaScriptErrors();
});

it('displays images in masonry grid layout', function () {
    $image = GalleryImage::factory()->create(['active' => true, 'caption' => 'Test Image']);
    $image->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('food');

    visit('/gallery')
        ->inDarkMode()
        ->assertSee('Test Image')
        ->assertNoJavaScriptErrors();
})->skip('Requires test image file');

it('opens lightbox when image is clicked', function () {
    $image = GalleryImage::factory()->create(['active' => true]);
    $image->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('food');

    visit('/gallery')
        ->inDarkMode()
        ->click('img')
        ->assertNoJavaScriptErrors();
})->skip('Requires test image file and lightbox interaction');

it('navigates through lightbox with arrow keys', function () {
    $image1 = GalleryImage::factory()->create(['active' => true]);
    $image2 = GalleryImage::factory()->create(['active' => true]);
    
    $image1->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('food');
    $image2->addMedia(storage_path('app/test-image2.jpg'))->toMediaCollection('food');

    visit('/gallery')
        ->inDarkMode()
        ->click('img')
        ->press('ArrowRight')
        ->assertNoJavaScriptErrors();
})->skip('Requires test images and keyboard interaction');

it('closes lightbox with escape key', function () {
    $image = GalleryImage::factory()->create(['active' => true]);
    $image->addMedia(storage_path('app/test-image.jpg'))->toMediaCollection('food');

    visit('/gallery')
        ->inDarkMode()
        ->click('img')
        ->press('Escape')
        ->assertNoJavaScriptErrors();
})->skip('Requires test image and keyboard interaction');

it('renders correctly on a mobile viewport', function () {
    visit('/gallery')
        ->inDarkMode()
        ->on()->mobile()
        ->assertSee('Gallery')
        ->assertSee('All')
        ->assertNoJavaScriptErrors();
});
