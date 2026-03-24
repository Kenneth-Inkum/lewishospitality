<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-950 antialiased">

        {{-- ===== NAVIGATION ===== --}}
        <header
            x-data="{
                scrolled: false,
                mobileOpen: false,
                init() {
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.scrollY > 40;
                    });
                }
            }"
            :class="scrolled
                ? 'bg-zinc-950/95 backdrop-blur-md border-b border-zinc-800 shadow-lg shadow-black/30'
                : 'bg-transparent border-b border-transparent'"
            class="fixed left-0 right-0 top-0 z-50 transition-all duration-300"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between sm:h-20">

                    {{-- Wordmark --}}
                    <a href="{{ route('home') }}" wire:navigate class="group flex flex-col leading-tight">
                        <span class="text-base font-semibold uppercase tracking-widest text-white transition-colors duration-200 group-hover:text-amber-400">Lewis</span>
                        <span class="text-[10px] font-medium uppercase tracking-[0.25em] text-zinc-400 transition-colors duration-200 group-hover:text-amber-500">Hospitality</span>
                    </a>

                    {{-- Desktop nav --}}
                    <nav class="hidden items-center gap-8 sm:flex">
                        <a href="{{ route('home') }}" wire:navigate
                           class="text-xs font-medium uppercase tracking-wide text-zinc-400 transition-colors duration-200 hover:text-white">
                            Home
                        </a>
                        <a href="{{ route('menu') }}" wire:navigate
                           class="text-xs font-medium uppercase tracking-wide text-zinc-400 transition-colors duration-200 hover:text-white">
                            Menu
                        </a>
                        <a href="{{ route('events') }}" wire:navigate
                           class="text-xs font-medium uppercase tracking-wide text-zinc-400 transition-colors duration-200 hover:text-white">
                            Events
                        </a>
                        <a href="{{ route('gallery') }}" wire:navigate
                           class="text-xs font-medium uppercase tracking-wide text-zinc-400 transition-colors duration-200 hover:text-white">
                            Gallery
                        </a>
                        <a href="{{ route('about') }}" wire:navigate
                           class="text-xs font-medium uppercase tracking-wide text-zinc-400 transition-colors duration-200 hover:text-white">
                            About
                        </a>
                        <a href="{{ route('contact') }}" wire:navigate
                           class="text-xs font-medium uppercase tracking-wide text-zinc-400 transition-colors duration-200 hover:text-white">
                            Contact
                        </a>
                        <a href="#"
                           class="ml-2 rounded-full bg-amber-600 px-5 py-2 text-xs font-semibold uppercase tracking-wide text-white transition-colors duration-200 hover:bg-amber-500">
                            Reserve a Table
                        </a>
                    </nav>

                    {{-- Mobile hamburger --}}
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="flex size-10 flex-col items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-zinc-800 hover:text-white sm:hidden"
                        aria-label="Toggle menu"
                    >
                        <span :class="mobileOpen ? 'rotate-45 translate-y-[7px]' : ''"
                              class="block h-px w-5 bg-current transition-all duration-200"></span>
                        <span :class="mobileOpen ? 'opacity-0' : ''"
                              class="mt-1.5 block h-px w-5 bg-current transition-all duration-200"></span>
                        <span :class="mobileOpen ? '-rotate-45 -translate-y-[7px]' : ''"
                              class="mt-1.5 block h-px w-5 bg-current transition-all duration-200"></span>
                    </button>
                </div>
            </div>

            {{-- Mobile menu panel --}}
            <div
                x-show="mobileOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="border-b border-zinc-800 bg-zinc-950/98 backdrop-blur-md sm:hidden"
            >
                <nav class="flex flex-col space-y-1 px-4 py-4">
                    <a href="{{ route('home') }}" wire:navigate @click="mobileOpen = false"
                       class="rounded-lg px-3 py-3 text-sm font-medium uppercase tracking-wide text-zinc-300 transition-colors hover:bg-zinc-800 hover:text-white">
                        Home
                    </a>
                    <a href="{{ route('menu') }}" wire:navigate @click="mobileOpen = false"
                       class="rounded-lg px-3 py-3 text-sm font-medium uppercase tracking-wide text-zinc-300 transition-colors hover:bg-zinc-800 hover:text-white">
                        Menu
                    </a>
                    <a href="{{ route('events') }}" wire:navigate @click="mobileOpen = false"
                       class="rounded-lg px-3 py-3 text-sm font-medium uppercase tracking-wide text-zinc-300 transition-colors hover:bg-zinc-800 hover:text-white">
                        Events
                    </a>
                    <a href="{{ route('gallery') }}" wire:navigate @click="mobileOpen = false"
                       class="rounded-lg px-3 py-3 text-sm font-medium uppercase tracking-wide text-zinc-300 transition-colors hover:bg-zinc-800 hover:text-white">
                        Gallery
                    </a>
                    <a href="{{ route('about') }}" wire:navigate @click="mobileOpen = false"
                       class="rounded-lg px-3 py-3 text-sm font-medium uppercase tracking-wide text-zinc-300 transition-colors hover:bg-zinc-800 hover:text-white">
                        About
                    </a>
                    <a href="{{ route('contact') }}" wire:navigate @click="mobileOpen = false"
                       class="rounded-lg px-3 py-3 text-sm font-medium uppercase tracking-wide text-zinc-300 transition-colors hover:bg-zinc-800 hover:text-white">
                        Contact
                    </a>
                    <div class="pb-1 pt-2">
                        <a href="#"
                           class="block rounded-full bg-amber-600 px-5 py-2.5 text-center text-sm font-semibold uppercase tracking-wide text-white transition-colors hover:bg-amber-500">
                            Reserve a Table
                        </a>
                    </div>
                </nav>
            </div>
        </header>

        {{-- ===== MAIN CONTENT ===== --}}
        <main class="pt-16 sm:pt-20">
            {{ $slot }}
        </main>

        {{-- ===== FOOTER ===== --}}
        <footer class="mt-24 border-t border-zinc-800 bg-zinc-950">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- Brand column --}}
                    <div class="lg:col-span-1">
                        <div class="mb-4 flex flex-col leading-tight">
                            <span class="text-lg font-semibold uppercase tracking-widest text-white">Lewis</span>
                            <span class="text-xs font-medium uppercase tracking-[0.25em] text-zinc-500">Hospitality</span>
                        </div>
                        <p class="max-w-xs text-sm leading-relaxed text-zinc-500">
                            An upscale dining group bringing warmth and craft to every table across the DC metro area.
                        </p>

                        {{-- Social icons --}}
                        <div class="mt-6 flex items-center gap-3">
                            <a href="#" aria-label="Instagram"
                               class="flex size-9 items-center justify-center rounded-full border border-zinc-800 text-zinc-500 transition-colors duration-200 hover:border-amber-600 hover:text-amber-400">
                                <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="Facebook"
                               class="flex size-9 items-center justify-center rounded-full border border-zinc-800 text-zinc-500 transition-colors duration-200 hover:border-amber-600 hover:text-amber-400">
                                <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="TikTok"
                               class="flex size-9 items-center justify-center rounded-full border border-zinc-800 text-zinc-500 transition-colors duration-200 hover:border-amber-600 hover:text-amber-400">
                                <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Quick links --}}
                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-zinc-400">Explore</h3>
                        <ul class="space-y-2.5">
                            <li><a href="{{ route('home') }}" wire:navigate class="text-sm text-zinc-500 transition-colors hover:text-amber-400">Home</a></li>
                            <li><a href="#" class="text-sm text-zinc-500 transition-colors hover:text-amber-400">Menu</a></li>
                            <li><a href="#" class="text-sm text-zinc-500 transition-colors hover:text-amber-400">Events</a></li>
                            <li><a href="#" class="text-sm text-zinc-500 transition-colors hover:text-amber-400">Reservations</a></li>
                            <li><a href="#" class="text-sm text-zinc-500 transition-colors hover:text-amber-400">Promotions</a></li>
                            @if (Route::has('contact'))
                                <li><a href="{{ route('contact') }}" wire:navigate class="text-sm text-zinc-500 transition-colors hover:text-amber-400">Contact</a></li>
                            @endif
                        </ul>
                    </div>

                    {{-- Penn Quarter --}}
                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-zinc-400">Penn Quarter</h3>
                        <address class="not-italic">
                            <p class="text-sm text-zinc-500">701 Pennsylvania Ave NW</p>
                            <p class="text-sm text-zinc-500">Washington, DC 20004</p>
                        </address>
                        <div class="mt-4 space-y-1.5">
                            <a href="tel:+12025550100" class="block text-sm text-zinc-500 transition-colors hover:text-amber-400">(202) 555-0100</a>
                            <a href="mailto:pennquarter@lewishospitality.com" class="block text-sm text-zinc-500 transition-colors hover:text-amber-400">pennquarter@lewishospitality.com</a>
                        </div>
                    </div>

                    {{-- Bethesda --}}
                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-zinc-400">Bethesda</h3>
                        <address class="not-italic">
                            <p class="text-sm text-zinc-500">4922 Hampden Ln</p>
                            <p class="text-sm text-zinc-500">Bethesda, MD 20814</p>
                        </address>
                        <div class="mt-4 space-y-1.5">
                            <a href="tel:+13015550200" class="block text-sm text-zinc-500 transition-colors hover:text-amber-400">(301) 555-0200</a>
                            <a href="mailto:bethesda@lewishospitality.com" class="block text-sm text-zinc-500 transition-colors hover:text-amber-400">bethesda@lewishospitality.com</a>
                        </div>
                    </div>
                </div>

                {{-- Bottom bar --}}
                <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-zinc-800/60 pt-8 sm:flex-row">
                    <p class="text-xs text-zinc-600">
                        &copy; {{ date('Y') }} Lewis Hospitality Group. All rights reserved.
                    </p>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-xs text-zinc-600 transition-colors hover:text-zinc-400">Privacy Policy</a>
                        <a href="#" class="text-xs text-zinc-600 transition-colors hover:text-zinc-400">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
