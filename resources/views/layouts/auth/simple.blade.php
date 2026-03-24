<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <div class="mb-4 flex justify-center">
                    <a href="{{ route('home') }}" class="group flex flex-col leading-tight" wire:navigate>
                        <span class="text-2xl font-semibold uppercase tracking-widest text-white transition-colors duration-200 group-hover:text-amber-400">Lewis</span>
                        <span class="text-sm font-medium uppercase tracking-[0.25em] text-zinc-400 transition-colors duration-200 group-hover:text-amber-500">Hospitality</span>
                    </a>
                </div>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
