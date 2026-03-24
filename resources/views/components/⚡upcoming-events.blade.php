<?php

use App\Models\Event;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    /** @return Collection<int, Event> */
    #[Computed]
    public function events(): Collection
    {
        return Event::query()
            ->published()
            ->upcoming()
            ->with('location')
            ->limit(3)
            ->get();
    }
};
?>

<div>
    {{-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh --}}
    
    @if ($this->events->isNotEmpty())
        <section class="bg-gradient-to-b from-zinc-950 to-zinc-900 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{-- Section Header --}}
                <div class="mb-12 text-center">
                    <div class="mb-4 flex items-center justify-center gap-4">
                        <div class="h-px w-12 bg-gradient-to-r from-transparent to-amber-600"></div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-500">What's Happening</p>
                        <div class="h-px w-12 bg-gradient-to-l from-transparent to-amber-600"></div>
                    </div>
                    <h2 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">Upcoming Events</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-base text-zinc-400">Join us for special occasions and celebrations</p>
                </div>

                {{-- Events Grid --}}
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    @foreach ($this->events as $index => $event)
                        <a href="{{ route('events.show', $event->slug) }}" wire:navigate
                           class="group overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-2 hover:border-amber-600/50 hover:shadow-2xl hover:shadow-amber-900/30 animate-in fade-in slide-in-from-bottom-8 duration-700"
                           style="animation-delay: {{ $index * 150 }}ms"
                        >
                            
                            @if ($event->getFirstMediaUrl('images'))
                                <div class="relative aspect-[16/9] overflow-hidden bg-zinc-800">
                                    <img 
                                        src="{{ $event->getFirstMediaUrl('images', 'thumb') }}" 
                                        alt="{{ $event->title }}"
                                        class="size-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:brightness-110"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-900/80 to-transparent"></div>
                                </div>
                            @else
                                <div class="aspect-[16/9] bg-gradient-to-br from-zinc-800 to-zinc-900 flex items-center justify-center">
                                    <flux:icon.calendar class="size-16 text-zinc-700" />
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <div class="mb-3 flex items-center gap-3 text-sm text-zinc-500">
                                    <div class="flex items-center gap-1.5">
                                        <flux:icon.calendar class="size-4" />
                                        <span>{{ $event->starts_at->format('M j, Y') }}</span>
                                    </div>
                                    @if ($event->location)
                                        <div class="flex items-center gap-1.5">
                                            <flux:icon.map-pin class="size-4" />
                                            <span>{{ $event->location->name }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <h3 class="mb-2 text-xl font-semibold text-white transition-colors group-hover:text-amber-400">
                                    {{ $event->title }}
                                </h3>
                                
                                @if ($event->description)
                                    <p class="mb-4 line-clamp-2 text-sm text-zinc-400">
                                        {{ strip_tags($event->description) }}
                                    </p>
                                @endif
                                
                                <div class="flex items-center gap-2 text-sm font-medium text-amber-400">
                                    <span>Learn More</span>
                                    <flux:icon.arrow-right class="size-4 transition-transform group-hover:translate-x-1" />
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- View All Events CTA --}}
                <div class="mt-12 text-center">
                    <a href="{{ route('events') }}" wire:navigate 
                       class="inline-flex items-center gap-2 rounded-full border border-amber-600/30 bg-amber-600/10 px-6 py-3 text-sm font-semibold text-amber-400 transition-all duration-200 hover:border-amber-600 hover:bg-amber-600/20">
                        <span>View All Events</span>
                        <flux:icon.arrow-right class="size-4" />
                    </a>
                </div>
            </div>
        </section>
    @endif
</div>