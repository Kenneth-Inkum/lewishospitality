<?php

use App\Models\Setting;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('About Us'), Layout('layouts.public')] class extends Component {
    #[Computed]
    public function originStory(): ?string
    {
        return Setting::get('about.origin_story');
    }

    #[Computed]
    public function philosophy(): ?string
    {
        return Setting::get('about.philosophy');
    }

    #[Computed]
    public function showTeam(): bool
    {
        return Setting::get('about.show_team', false);
    }

    #[Computed]
    public function showAwards(): bool
    {
        return Setting::get('about.show_awards', false);
    }

    #[Computed]
    public function showValues(): bool
    {
        return Setting::get('about.show_values', true);
    }

    #[Computed]
    public function awards(): array
    {
        return Setting::get('about.awards_list', []);
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
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">Our Story</h1>
                <p class="mt-3 text-base text-zinc-400">Two decades of bringing people together around exceptional food.</p>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

        {{-- Origin Story --}}
        @if ($this->originStory)
            <div class="mb-16">
                <div class="mb-8 flex items-center gap-4">
                    <div class="h-px w-8 bg-gradient-to-r from-amber-600 to-transparent"></div>
                    <h2 class="text-2xl font-semibold tracking-tight text-white">How It Started</h2>
                </div>
                <div class="prose prose-invert prose-zinc max-w-none rounded-2xl border border-zinc-800 bg-zinc-900 p-8 shadow-xl shadow-black/30">
                    {!! $this->originStory !!}
                </div>
            </div>
        @endif

        {{-- Philosophy / Values --}}
        @if ($this->showValues && $this->philosophy)
            <div class="mb-16">
                <div class="mb-8 flex items-center gap-4">
                    <div class="h-px w-8 bg-gradient-to-r from-amber-600 to-transparent"></div>
                    <h2 class="text-2xl font-semibold tracking-tight text-white">Our Philosophy</h2>
                </div>
                <div class="prose prose-invert prose-zinc max-w-none rounded-2xl border border-zinc-800 bg-zinc-900 p-8 shadow-xl shadow-black/30">
                    {!! $this->philosophy !!}
                </div>
            </div>
        @endif

        {{-- Awards / Press --}}
        @if ($this->showAwards)
            <div class="mb-16">
                <div class="mb-8 flex items-center gap-4">
                    <div class="h-px w-8 bg-gradient-to-r from-amber-600 to-transparent"></div>
                    <h2 class="text-2xl font-semibold tracking-tight text-white">Recognition</h2>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @if (is_array($this->awards) && count($this->awards) > 0)
                        @foreach ($this->awards as $award)
                            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30">
                                <div class="mb-3 flex size-12 items-center justify-center rounded-full bg-amber-600/15 ring-1 ring-amber-600/30">
                                    <flux:icon.trophy class="size-6 text-amber-400" />
                                </div>
                                <h3 class="font-semibold text-white">{{ $award['title'] ?? 'Award' }}</h3>
                                <p class="mt-1 text-sm text-zinc-500">{{ $award['year'] ?? '' }}</p>
                                @if (isset($award['description']))
                                    <p class="mt-2 text-sm text-zinc-400">{{ $award['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full rounded-2xl border border-zinc-800 bg-zinc-900 p-8 text-center">
                            <p class="text-zinc-500">Awards and recognition will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Team Section --}}
        @if ($this->showTeam)
            <div class="mb-16">
                <div class="mb-8 flex items-center gap-4">
                    <div class="h-px w-8 bg-gradient-to-r from-amber-600 to-transparent"></div>
                    <h2 class="text-2xl font-semibold tracking-tight text-white">Our Team</h2>
                </div>
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-8 text-center shadow-xl shadow-black/30">
                    <p class="text-zinc-500">Team member profiles will appear here once configured.</p>
                </div>
            </div>
        @endif

    </section>
</div>