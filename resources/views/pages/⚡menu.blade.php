<?php

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Menu'), Layout('layouts.public')] class extends Component {
    public array $activeTags = [];

    /** @return Collection<int, MenuCategory> */
    #[Computed]
    public function categories(): Collection
    {
        return MenuCategory::query()
            ->where('active', true)
            ->orderBy('sort_order')
            ->with(['items' => function ($query) {
                $query->where('active', true)
                    ->where(function ($q) {
                        $q->where('available_always', true)
                            ->orWhere(function ($q2) {
                                $q2->where('available_from', '<=', now())
                                    ->where('available_until', '>=', now());
                            });
                    })
                    ->orderBy('sort_order');
            }])
            ->get()
            ->filter(fn($category) => $category->items->isNotEmpty());
    }

    /** @return array<string> */
    #[Computed]
    public function allDietaryTags(): array
    {
        return MenuItem::query()
            ->where('active', true)
            ->get()
            ->pluck('dietary_tags')
            ->flatten()
            ->filter(fn($tag) => !empty($tag))
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }
};
?>

<div x-data="{
    search: '',
    activeTags: $wire.entangle('activeTags').live,
    matchesSearch(name, description) {
        if (!this.search) return true;
        const searchLower = this.search.toLowerCase();
        return name.toLowerCase().includes(searchLower) || 
               (description && description.toLowerCase().includes(searchLower));
    },
    matchesTags(itemTags) {
        if (this.activeTags.length === 0) return true;
        if (!itemTags || itemTags.length === 0) return false;
        return this.activeTags.every(tag => itemTags.includes(tag));
    },
    toggleTag(tag) {
        const index = this.activeTags.indexOf(tag);
        if (index > -1) {
            this.activeTags.splice(index, 1);
        } else {
            this.activeTags.push(tag);
        }
        this.$nextTick(() => this.$dispatch('items-filtered'));
    }
}" 
x-init="$watch('search', () => $nextTick(() => $dispatch('items-filtered')))"
>
    {{-- Hero Section --}}
    <section class="relative flex h-[300px] items-center overflow-hidden bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-800">
        <div class="pointer-events-none absolute -right-24 -top-24 size-[480px] rounded-full border border-zinc-700/20 opacity-40"></div>
        <div class="pointer-events-none absolute -right-12 -top-12 size-[320px] rounded-full border border-zinc-700/20 opacity-30"></div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-amber-600/50 to-transparent"></div>

        <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-500">Lewis Hospitality</p>
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">Our Menu</h1>
                <p class="mt-3 text-base text-zinc-400">Crafted with passion, served with pride.</p>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        {{-- Search & Filters --}}
        <div class="mb-12 space-y-6">
            {{-- Search Bar --}}
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <flux:icon.magnifying-glass class="size-5 text-zinc-500" />
                </div>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search menu items..."
                    class="w-full rounded-xl border border-zinc-700 bg-zinc-900 py-3 pl-12 pr-4 text-white placeholder-zinc-500 transition-colors focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600"
                />
            </div>

            {{-- Dietary Tag Filters --}}
            @if ($this->allDietaryTags)
                <div>
                    <div class="mb-3 flex items-center gap-2">
                        <flux:icon.funnel class="size-4 text-amber-500" />
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-zinc-400">Dietary Preferences</h3>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($this->allDietaryTags as $tag)
                            <button
                                @click="toggleTag('{{ $tag }}')"
                                :class="activeTags.includes('{{ $tag }}')
                                    ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30'
                                    : 'border border-zinc-700 bg-transparent text-zinc-400 hover:border-zinc-500 hover:text-white'"
                                class="rounded-full px-4 py-2 text-sm font-medium transition-all duration-200"
                            >
                                {{ str_replace('_', ' ', ucfirst($tag)) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- PDF Download --}}
            <div class="flex items-center justify-between border-t border-zinc-800 pt-6">
                <p class="text-sm text-zinc-500">Download our menu as a PDF</p>
                <div>
                    <livewire:download-menu-pdf />
                </div>
            </div>
        </div>

        {{-- Menu Categories --}}
        <div class="space-y-16">
            @forelse ($this->categories as $category)
                <div x-data="{ 
                    hasVisibleItems: false,
                    checkVisibility() {
                        this.$nextTick(() => {
                            const items = this.$el.querySelectorAll('[data-menu-item]');
                            this.hasVisibleItems = Array.from(items).some(item => 
                                item.style.display !== 'none' && !item.hasAttribute('hidden')
                            );
                        });
                    }
                }" 
                x-init="checkVisibility()"
                @items-filtered.window="checkVisibility()"
                x-show="hasVisibleItems">
                    {{-- Category Header --}}
                    <div class="mb-8 flex items-center gap-4">
                        <div class="h-px w-8 bg-gradient-to-r from-amber-600 to-transparent"></div>
                        <h2 class="text-3xl font-semibold tracking-tight text-white">{{ $category->name }}</h2>
                    </div>

                    {{-- Menu Items Grid --}}
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        @foreach ($category->items as $item)
                            <div
                                data-menu-item
                                x-show="matchesSearch('{{ addslashes($item->name) }}', '{{ addslashes(strip_tags($item->description ?? '')) }}') && matchesTags(@js($item->dietary_tags ?? []))"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                @transitionend.window="$dispatch('items-filtered')"
                                class="group relative overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30 transition-all duration-200 hover:border-amber-600/50"
                            >
                                @if ($item->featured)
                                    <div class="absolute right-4 bottom-4">
                                        <div class="rounded-full bg-amber-600/20 px-2 py-1 ring-1 ring-amber-600/40">
                                            <flux:icon.star class="size-3 text-amber-400" />
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-start gap-4">
                                    @if ($item->hasMedia('images'))
                                        <div class="size-20 shrink-0 overflow-hidden rounded-lg">
                                            <img
                                                src="{{ $item->getFirstMediaUrl('images', 'thumb') }}"
                                                alt="{{ $item->name }}"
                                                class="size-full object-cover"
                                            />
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4 mb-2">
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-white group-hover:text-amber-400 transition-colors">
                                                    {{ $item->name }}
                                                </h3>
                                                @if ($item->dietary_tags && count($item->dietary_tags) > 0)
                                                    <div class="mt-1.5 flex flex-wrap gap-1">
                                                        @foreach ($item->dietary_tags as $tag)
                                                            <span class="rounded-full bg-amber-600/10 px-2 py-0.5 text-xs font-medium text-amber-400 ring-1 ring-amber-600/20">
                                                                {{ str_replace('_', ' ', ucfirst($tag)) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="shrink-0 text-lg font-semibold text-amber-400 whitespace-nowrap">
                                                ${{ number_format($item->price, 2) }}
                                            </p>
                                        </div>
                                        @if ($item->description)
                                            <p class="text-sm text-zinc-400 leading-relaxed">
                                                {{ $item->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-12 text-center">
                    <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-zinc-800">
                        <flux:icon.document-text class="size-8 text-zinc-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-white">No menu items available</h3>
                    <p class="mt-2 text-sm text-zinc-500">Check back soon for our latest offerings.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>