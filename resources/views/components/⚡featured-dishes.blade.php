<?php

use App\Models\MenuItem;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    /** @return Collection<int, MenuItem> */
    #[Computed]
    public function dishes(): Collection
    {
        return MenuItem::query()
            ->where('featured', true)
            ->where('active', true)
            ->with('category')
            ->limit(6)
            ->get();
    }
};
?>

<div>
    @if ($this->dishes->isNotEmpty())
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{-- Section Header --}}
                <div class="mb-12 text-center">
                    <div class="mb-4 flex items-center justify-center gap-4">
                        <div class="h-px w-12 bg-gradient-to-r from-transparent to-amber-600"></div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-500">Signature Dishes</p>
                        <div class="h-px w-12 bg-gradient-to-l from-transparent to-amber-600"></div>
                    </div>
                    <h2 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">Featured Menu</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-base text-zinc-400">Handpicked selections from our culinary team</p>
                </div>

                {{-- Dishes Grid --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->dishes as $index => $dish)
                        <div 
                            class="group overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 transition-all duration-300 hover:-translate-y-2 hover:border-amber-600/50 hover:shadow-2xl hover:shadow-amber-900/30 animate-in fade-in slide-in-from-bottom-8 duration-700"
                            style="animation-delay: {{ $index * 100 }}ms"
                        >
                            @if ($dish->getFirstMediaUrl('images'))
                                <div class="relative aspect-[4/3] overflow-hidden bg-zinc-800">
                                    <img 
                                        src="{{ $dish->getFirstMediaUrl('images', 'thumb') }}" 
                                        alt="{{ $dish->name }}"
                                        class="size-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:brightness-110"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-900/60 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                </div>
                            @else
                                <div class="aspect-[4/3] bg-gradient-to-br from-zinc-800 to-zinc-900 flex items-center justify-center">
                                    <flux:icon.photo class="size-16 text-zinc-700" />
                                </div>
                            @endif
                            
                            <div class="p-6">
                                @if ($dish->category)
                                    <span class="mb-2 inline-block rounded-full bg-amber-600/10 px-3 py-1 text-xs font-medium uppercase tracking-wide text-amber-400 ring-1 ring-amber-600/20">
                                        {{ $dish->category->name }}
                                    </span>
                                @endif
                                
                                <h3 class="mb-2 text-xl font-semibold text-white">{{ $dish->name }}</h3>
                                
                                @if ($dish->description)
                                    <p class="mb-4 line-clamp-2 text-sm text-zinc-400">{{ $dish->description }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-semibold text-amber-400">${{ number_format($dish->price, 2) }}</span>
                                    
                                    @if ($dish->dietary_flags)
                                        <div class="flex gap-1">
                                            @foreach ($dish->dietary_flags as $flag)
                                                <span class="rounded bg-zinc-800 px-2 py-1 text-[10px] uppercase tracking-wide text-zinc-500">
                                                    {{ $flag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- View Full Menu CTA --}}
                <div class="mt-12 text-center">
                    <a href="#" class="inline-flex items-center gap-2 rounded-full border border-amber-600/30 bg-amber-600/10 px-6 py-3 text-sm font-semibold text-amber-400 transition-all duration-200 hover:border-amber-600 hover:bg-amber-600/20">
                        <span>View Full Menu</span>
                        <flux:icon.arrow-right class="size-4" />
                    </a>
                </div>
            </div>
        </section>
    @endif
</div>
{{-- It always seems impossible until it is done. - Nelson Mandela --}}