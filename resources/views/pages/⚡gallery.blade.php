<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

new #[Title('Gallery'), Layout('layouts.public')] class extends Component {
    public string $collection = 'all';

    /** @var array<string> */
    public array $collections = ['all', 'food', 'ambience', 'events', 'behind_the_scenes'];

    /** @return Collection<int, Media> */
    #[Computed]
    public function images(): Collection
    {
        $query = Media::query()
            ->where('model_type', 'App\\Models\\GalleryImage')
            ->whereIn('model_id', function ($q) {
                $q->select('id')
                    ->from('gallery_images')
                    ->where('active', true);
            })
            ->orderBy('order_column');

        if ($this->collection !== 'all') {
            $query->where('collection_name', $this->collection);
        }

        return $query->get();
    }

    public function updatedCollection(): void
    {
        unset($this->images);
    }
};
?>

<div 
    x-data="{ 
        lightboxOpen: false, 
        currentIndex: 0,
        open(index) { 
            this.currentIndex = index; 
            this.lightboxOpen = true; 
            document.body.style.overflow = 'hidden';
        },
        close() { 
            this.lightboxOpen = false; 
            document.body.style.overflow = '';
        },
        next() { 
            this.currentIndex = (this.currentIndex + 1) % this.getImages().length; 
        },
        prev() { 
            this.currentIndex = (this.currentIndex - 1 + this.getImages().length) % this.getImages().length; 
        },
        getImages() {
            return Array.from(document.querySelectorAll('[data-lightbox-image]')).map(el => ({
                url: el.dataset.url,
                thumb: el.dataset.thumb,
                alt: el.dataset.alt
            }));
        },
        handleKey(e) {
            if (!this.lightboxOpen) return;
            if (e.key === 'Escape') this.close();
            if (e.key === 'ArrowRight') this.next();
            if (e.key === 'ArrowLeft') this.prev();
        }
    }" 
    @keydown.window="handleKey"
>

    {{-- Hero Section --}}
    <section class="relative flex h-[300px] items-center overflow-hidden bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-800">
        <div class="pointer-events-none absolute -right-24 -top-24 size-[480px] rounded-full border border-zinc-700/20 opacity-40"></div>
        <div class="pointer-events-none absolute -right-12 -top-12 size-[320px] rounded-full border border-zinc-700/20 opacity-30"></div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-amber-600/50 to-transparent"></div>

        <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-500">Lewis Hospitality</p>
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">Gallery</h1>
                <p class="mt-3 text-base text-zinc-400">A visual journey through our food, spaces, and memorable moments.</p>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

        {{-- Collection Filter Tabs --}}
        <div class="mb-12" x-data="{ collection: $wire.entangle('collection').live }">
            <div class="flex flex-wrap gap-2">
                @foreach ($this->collections as $col)
                    <button
                        @click="collection = '{{ $col }}'"
                        :class="collection === '{{ $col }}'
                            ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30'
                            : 'border border-zinc-700 bg-transparent text-zinc-400 hover:border-zinc-500 hover:text-white'"
                        class="rounded-full px-5 py-2 text-sm font-medium capitalize transition-all duration-200"
                    >
                        {{ str_replace('_', ' ', $col) }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Masonry Grid --}}
        @if ($this->images->isEmpty())
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-12 text-center">
                <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-zinc-800">
                    <flux:icon.photo class="size-8 text-zinc-500" />
                </div>
                <h3 class="text-lg font-semibold text-white">No images found</h3>
                <p class="mt-2 text-sm text-zinc-500">Images will appear here once added to the gallery.</p>
            </div>
        @else
            <div class="columns-1 gap-4 sm:columns-2 lg:columns-3">
                @foreach ($this->images as $index => $image)
                    <div 
                        wire:key="img-{{ $image->id }}"
                        @click="open({{ $index }})"
                        data-lightbox-image
                        data-url="{{ $image->getUrl() }}"
                        data-thumb="{{ $image->getUrl('thumb') }}"
                        data-alt="{{ $image->model?->caption ?? $image->name }}"
                        class="mb-4 break-inside-avoid cursor-pointer overflow-hidden rounded-xl ring-1 ring-zinc-800 transition-all duration-200 hover:ring-amber-600/50"
                    >
                        <img 
                            src="{{ $image->getUrl('thumb') }}" 
                            srcset="{{ $image->getSrcset() }}"
                            sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                            alt="{{ $image->model?->caption ?? $image->name }}"
                            loading="lazy"
                            class="w-full object-cover"
                        />
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Lightbox --}}
    <div 
        x-show="lightboxOpen" 
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-sm"
        @click.self="close"
    >
        {{-- Close Button --}}
        <button @click="close" class="absolute right-4 top-4 rounded-full bg-zinc-800/80 p-2 text-white transition-colors hover:bg-zinc-700">
            <flux:icon.x-mark class="size-6" />
        </button>

        {{-- Navigation --}}
        <button 
            @click.stop="prev" 
            class="absolute left-4 rounded-full bg-zinc-800/80 p-3 text-white transition-colors hover:bg-zinc-700 sm:left-8"
        >
            <flux:icon.chevron-left class="size-6" />
        </button>
        <button 
            @click.stop="next" 
            class="absolute right-4 rounded-full bg-zinc-800/80 p-3 text-white transition-colors hover:bg-zinc-700 sm:right-8"
        >
            <flux:icon.chevron-right class="size-6" />
        </button>

        {{-- Image --}}
        <div class="max-h-[85vh] max-w-[90vw]">
            <img 
                :src="getImages()[currentIndex]?.url" 
                :alt="getImages()[currentIndex]?.alt"
                class="max-h-[85vh] max-w-[90vw] object-contain"
            />
        </div>

        {{-- Counter --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 rounded-full bg-zinc-800/80 px-4 py-2 text-sm text-white">
            <span x-text="currentIndex + 1"></span> / <span x-text="getImages().length"></span>
        </div>
    </div>
</div>