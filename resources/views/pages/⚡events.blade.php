<?php

use App\Models\Event;
use App\Models\HappyHour;
use App\Models\Promotion;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Events'), Layout('layouts.public')] class extends Component {
    public string $filter = 'all';

    /** @return Collection<int, Event> */
    #[Computed]
    public function events(): Collection
    {
        $query = Event::query()
            ->published()
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at');

        if ($this->filter === 'events') {
            return $query->get();
        }

        if ($this->filter === 'seasonal') {
            return $query->whereNotNull('ends_at')->get();
        }

        return $query->get();
    }

    /** @return Collection<int, Promotion> */
    #[Computed]
    public function promotions(): Collection
    {
        return Promotion::query()
            ->active()
            ->where('show_on_homepage', true)
            ->get();
    }

    /** @return Collection<int, HappyHour> */
    #[Computed]
    public function happyHours(): Collection
    {
        return HappyHour::query()
            ->active()
            ->where('day_of_week', (int) now()->format('w'))
            ->with('location')
            ->get();
    }
};
?>

<div>
    {{-- Hero Section --}}
    <section class="relative flex h-[300px] items-center overflow-hidden bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-800">
        <div class="pointer-events-none absolute -right-24 -top-24 size-[480px] rounded-full border border-zinc-700/20 opacity-40"></div>
        <div class="pointer-events-none absolute -right-12 -top-12 size-[320px] rounded-full border border-zinc-700/20 opacity-30"></div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-amber-600/50 to-transparent"></div>

        <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-500">Lewis Hospitality</p>
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">Events & Happenings</h1>
                <p class="mt-3 text-base text-zinc-400">Join us for special occasions, happy hours, and seasonal celebrations.</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        @if ($this->promotions->isNotEmpty())
            <livewire:promotions-bar />
        @endif

        <div class="mb-12" x-data="{ filter: @entangle('filter') }">
            <div class="flex flex-wrap gap-2">
                <button
                    @click="filter = 'all'"
                    :class="filter === 'all'
                        ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30'
                        : 'border border-zinc-700 bg-transparent text-zinc-400 hover:border-zinc-500 hover:text-white'"
                    class="rounded-full px-5 py-2 text-sm font-medium transition-all duration-200"
                >All</button>
                <button
                    @click="filter = 'events'"
                    :class="filter === 'events'
                        ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30'
                        : 'border border-zinc-700 bg-transparent text-zinc-400 hover:border-zinc-500 hover:text-white'"
                    class="rounded-full px-5 py-2 text-sm font-medium transition-all duration-200"
                >Events</button>
                <button
                    @click="filter = 'happy_hours'"
                    :class="filter === 'happy_hours'
                        ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30'
                        : 'border border-zinc-700 bg-transparent text-zinc-400 hover:border-zinc-500 hover:text-white'"
                    class="rounded-full px-5 py-2 text-sm font-medium transition-all duration-200"
                >Happy Hours</button>
                <button
                    @click="filter = 'seasonal'"
                    :class="filter === 'seasonal'
                        ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30'
                        : 'border border-zinc-700 bg-transparent text-zinc-400 hover:border-zinc-500 hover:text-white'"
                    class="rounded-full px-5 py-2 text-sm font-medium transition-all duration-200"
                >Seasonal</button>
            </div>
        </div>

        @if ($this->filter === 'all' || $this->filter === 'happy_hours')
            @if ($this->happyHours->isNotEmpty())
                <div class="mb-16">
                    <div class="mb-8 flex items-center gap-4">
                        <div class="h-px w-8 bg-gradient-to-r from-amber-600 to-transparent"></div>
                        <h2 class="text-2xl font-semibold tracking-tight text-white">Today's Happy Hours</h2>
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->happyHours as $happyHour)
                            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30">
                                <div class="mb-4 flex items-center gap-3">
                                    <div class="flex size-10 items-center justify-center rounded-full bg-amber-600/15 ring-1 ring-amber-600/30">
                                        <flux:icon.clock class="size-5 text-amber-400" />
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white">{{ $happyHour->location->name }}</h3>
                                        @if ($happyHour->label)
                                            <p class="text-xs text-amber-500">{{ $happyHour->label }}</p>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-sm text-zinc-400">
                                    {{ \Carbon\Carbon::parse($happyHour->starts_at)->format('g:i A') }} – {{ \Carbon\Carbon::parse($happyHour->ends_at)->format('g:i A') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        @if ($this->filter !== 'happy_hours')
            <div>
                <div class="mb-8 flex items-center gap-4">
                    <div class="h-px w-8 bg-gradient-to-r from-amber-600 to-transparent"></div>
                    <h2 class="text-2xl font-semibold tracking-tight text-white">
                        {{ $this->filter === 'seasonal' ? 'Seasonal Events' : 'Upcoming Events' }}
                    </h2>
                </div>

                @if ($this->events->isEmpty())
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-12 text-center">
                        <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-zinc-800">
                            <flux:icon.calendar class="size-8 text-zinc-500" />
                        </div>
                        <h3 class="text-lg font-semibold text-white">No events found</h3>
                        <p class="mt-2 text-sm text-zinc-500">Check back soon for upcoming events and special occasions.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->events as $event)
                            <a
                                href="{{ route('events.show', $event->slug) }}"
                                wire:navigate
                                class="group overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 shadow-xl shadow-black/30 transition-all duration-200 hover:border-zinc-700 hover:shadow-2xl"
                            >
                                <div class="aspect-video bg-gradient-to-br from-zinc-800 to-zinc-900">
                                    @if ($event->getFirstMediaUrl('images'))
                                        <img 
                                            src="{{ $event->getFirstMediaUrl('images', 'thumb') }}" 
                                            alt="{{ $event->title }}" 
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" 
                                            loading="lazy" 
                                        />
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <flux:icon.calendar class="size-12 text-zinc-600" />
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <div class="mb-3 flex items-center gap-2 text-xs text-zinc-500">
                                        <flux:icon.calendar class="size-3.5" />
                                        <span>{{ $event->starts_at->format('F j, Y') }}</span>
                                        @if ($event->ends_at)
                                            <span>– {{ $event->ends_at->format('F j, Y') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="mb-2 text-lg font-semibold text-white transition-colors group-hover:text-amber-400">{{ $event->title }}</h3>
                                    <p class="line-clamp-2 text-sm text-zinc-400">{{ Str::limit(strip_tags($event->description), 120) }}</p>
                                    <div class="mt-4 flex items-center gap-2 text-xs font-medium text-amber-500">
                                        <span>Learn more</span>
                                        <flux:icon.arrow-right class="size-3 transition-transform group-hover:translate-x-1" />
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </section>
</div>