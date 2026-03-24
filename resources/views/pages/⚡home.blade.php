<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Welcome to Lewis Hospitality'), Layout('layouts.public')] class extends Component {
    //
};
?>

<div>
    {{-- Hero Section --}}
    <section class="relative flex min-h-[700px] items-center overflow-hidden bg-zinc-950">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0">
            <img 
                src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop" 
                alt="Restaurant ambiance"
                class="size-full object-cover opacity-50"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-zinc-950/90 via-zinc-950/70 to-zinc-950/60"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/80 via-transparent to-transparent"></div>
        </div>

        {{-- Animated Decorative Elements --}}
        <div class="pointer-events-none absolute -right-32 -top-32 size-[600px] animate-pulse rounded-full border border-amber-600/10 opacity-40 duration-[3000ms]"></div>
        <div class="pointer-events-none absolute -right-16 -top-16 size-[400px] animate-pulse rounded-full border border-amber-600/20 opacity-30 delay-1000 duration-[3000ms]"></div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-amber-600/50 to-transparent"></div>

        <div class="relative mx-auto w-full max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="max-w-3xl space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-500 animate-in fade-in slide-in-from-left-4 delay-150 duration-700">Est. 2004</p>
                <h1 class="text-5xl font-semibold tracking-tight text-white sm:text-6xl lg:text-7xl animate-in fade-in slide-in-from-bottom-6 delay-300 duration-1000">
                    Exceptional Dining<br>
                    <span class="bg-gradient-to-r from-amber-400 to-amber-600 bg-clip-text text-transparent">Experiences</span>
                </h1>
                <p class="text-lg leading-relaxed text-zinc-300 sm:text-xl animate-in fade-in slide-in-from-bottom-4 delay-500 duration-1000">
                    Two decades of bringing people together around exceptional food in the heart of Washington, DC.
                </p>
                <div class="flex flex-wrap gap-4 pt-4 animate-in fade-in slide-in-from-bottom-4 delay-700 duration-1000">
                    <a href="{{ route('reservations') }}" wire:navigate class="group inline-flex items-center gap-2 rounded-full bg-amber-600 px-8 py-4 text-sm font-semibold uppercase tracking-wide text-white shadow-xl shadow-amber-900/40 transition-all duration-300 hover:scale-105 hover:bg-amber-500 hover:shadow-2xl hover:shadow-amber-900/50">
                        <span>Reserve a Table</span>
                        <flux:icon.arrow-right class="size-4 transition-transform duration-300 group-hover:translate-x-1" />
                    </a>
                    <a href="{{ route('events') }}" wire:navigate class="group inline-flex items-center gap-2 rounded-full border border-zinc-700 bg-zinc-900/50 px-8 py-4 text-sm font-semibold uppercase tracking-wide text-white backdrop-blur-sm transition-all duration-300 hover:scale-105 hover:border-amber-600/50 hover:bg-zinc-800 hover:shadow-lg">
                        <span>View Events</span>
                        <flux:icon.arrow-right class="size-4 transition-transform duration-300 group-hover:translate-x-1" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Promotions Bar --}}
    <section class="pt-12">
        <livewire:promotions-bar />
    </section>

    {{-- Featured Dishes --}}
    <livewire:featured-dishes />

    {{-- Upcoming Events --}}
    <livewire:upcoming-events />

    {{-- Instagram Feed --}}
    <livewire:instagram-feed />

    {{-- CTA Section --}}
    <section class="relative overflow-hidden bg-zinc-950 py-20">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0">
            <img 
                src="https://images.unsplash.com/photo-1559339352-11d035aa65de?w=1600&q=80" 
                alt="Restaurant interior"
                class="size-full object-cover opacity-20"
            />
            <div class="absolute inset-0 bg-gradient-to-br from-amber-950/40 via-zinc-900/90 to-zinc-950/95"></div>
        </div>

        {{-- Animated Background Elements --}}
        <div class="pointer-events-none absolute left-0 top-0 size-96 animate-pulse rounded-full bg-amber-600/5 blur-3xl duration-[4000ms]"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 size-96 animate-pulse rounded-full bg-amber-600/5 blur-3xl delay-1000 duration-[4000ms]"></div>
        
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl border border-amber-600/30 bg-gradient-to-br from-amber-950/20 to-zinc-900/50 p-12 text-center backdrop-blur-sm shadow-2xl shadow-amber-900/10 animate-in fade-in slide-in-from-bottom-8 duration-1000">
                <div class="pointer-events-none absolute -right-20 -top-20 size-40 rounded-full bg-amber-600/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-20 size-40 rounded-full bg-amber-600/10 blur-2xl"></div>
                
                <div class="relative">
                    <h2 class="mb-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl animate-in fade-in slide-in-from-bottom-4 delay-200 duration-700">
                        Ready to Experience Lewis Hospitality?
                    </h2>
                    <p class="mx-auto mb-8 max-w-2xl text-lg leading-relaxed text-zinc-300 animate-in fade-in slide-in-from-bottom-4 delay-300 duration-700">
                        Reserve your table today and discover why we've been a DC dining destination for over 20 years.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-4 animate-in fade-in slide-in-from-bottom-4 delay-500 duration-700">
                        <a href="{{ route('reservations') }}" wire:navigate class="group inline-flex items-center gap-2 rounded-full bg-amber-600 px-8 py-4 text-sm font-semibold uppercase tracking-wide text-white shadow-xl shadow-amber-900/40 transition-all duration-300 hover:scale-105 hover:bg-amber-500 hover:shadow-2xl hover:shadow-amber-900/50">
                            <flux:icon.calendar class="size-5 transition-transform duration-300 group-hover:scale-110" />
                            <span>Make a Reservation</span>
                        </a>
                        <a href="{{ route('contact') }}" wire:navigate class="group inline-flex items-center gap-2 rounded-full border border-zinc-700 bg-zinc-900/50 px-8 py-4 text-sm font-semibold uppercase tracking-wide text-white backdrop-blur-sm transition-all duration-300 hover:scale-105 hover:border-amber-600/50 hover:bg-zinc-800 hover:shadow-lg">
                            <flux:icon.envelope class="size-5 transition-transform duration-300 group-hover:scale-110" />
                            <span>Contact Us</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>