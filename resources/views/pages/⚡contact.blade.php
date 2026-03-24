<?php

use App\Models\ContactSubmission;
use App\Models\Location;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Contact Us'), Layout('layouts.public')] class extends Component {
    public ?int $locationId = null;
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $message = '';
    public bool $submitted = false;
    public string $successName = '';

    public function mount(): void
    {
        $this->locationId = Location::query()->active()
            ->orderByDesc('is_primary')
            ->value('id');
    }

    /** @return Collection<int, Location> */
    #[Computed]
    public function locations(): Collection
    {
        return Location::query()->active()->get(['id', 'name']);
    }

    #[Computed]
    public function selectedLocation(): ?Location
    {
        if ($this->locationId === null) {
            return null;
        }

        return Location::with(['hours' => fn ($q) => $q->orderBy('day_of_week')])->find($this->locationId);
    }

    #[Computed]
    public function todayDayOfWeek(): int
    {
        return (int) now()->format('w');
    }

    public function submit(): void
    {
        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'subject' => ['required', 'string', 'max:255'],
                'message' => ['required', 'string', 'max:5000'],
                'locationId' => ['nullable', 'exists:locations,id'],
            ],
            [],
            ['locationId' => 'location'],
        );

        ContactSubmission::create([
            'location_id' => $this->locationId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        $this->successName = $this->name;
        $this->submitted = true;
        $this->reset(['name', 'email', 'subject', 'message']);
    }
};
?>

<div>
    {{-- ===== HERO ===== --}}
    <section class="relative flex h-[300px] items-center overflow-hidden bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-800">

        {{-- Decorative concentric rings --}}
        <div class="pointer-events-none absolute -right-24 -top-24 size-[480px] rounded-full border border-zinc-700/20 opacity-40"></div>
        <div class="pointer-events-none absolute -right-12 -top-12 size-[320px] rounded-full border border-zinc-700/20 opacity-30"></div>
        <div class="pointer-events-none absolute -right-4 top-8 size-[200px] rounded-full border border-zinc-700/15 opacity-20"></div>

        {{-- Placeholder pattern texture --}}
        <x-placeholder-pattern class="absolute inset-0 stroke-zinc-700/10 [mask-image:radial-gradient(ellipse_at_right,transparent_30%,black)]" />

        {{-- Amber separator at bottom of hero --}}
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-amber-600/50 to-transparent"></div>

        <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-500">Lewis Hospitality</p>
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">Let's Connect</h1>
                <p class="mt-3 text-base text-zinc-400">We're always happy to hear from our guests.</p>
            </div>

            {{-- Location tab pills --}}
            @if ($this->locations->count() > 1)
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach ($this->locations as $location)
                        <button
                            wire:click="$set('locationId', {{ $location->id }})"
                            wire:key="tab-{{ $location->id }}"
                            @class([
                                'rounded-full px-5 py-2 text-sm font-medium transition-all duration-200',
                                'bg-amber-600 text-white shadow-md shadow-amber-900/30' => $this->locationId === $location->id,
                                'border border-zinc-700 bg-transparent text-zinc-400 hover:border-zinc-500 hover:text-white' => $this->locationId !== $location->id,
                            ])
                        >
                            {{ $location->name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===== MAIN CONTENT ===== --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-[1fr_minmax(0,420px)]">

            {{-- ===== LEFT: Location info ===== --}}
            <div class="space-y-10">
                @if ($this->selectedLocation)

                    {{-- Location name --}}
                    <div class="flex items-center gap-4">
                        <div class="h-px w-8 bg-gradient-to-r from-amber-600 to-transparent"></div>
                        <h2 class="text-2xl font-semibold tracking-tight text-white">
                            {{ $this->selectedLocation->name }}
                        </h2>
                    </div>

                    {{-- Address --}}
                    <div class="flex items-start gap-4">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-amber-600/15 ring-1 ring-amber-600/30">
                            <flux:icon.map-pin class="size-5 text-amber-400" />
                        </div>
                        <div class="pt-1">
                            <p class="text-sm font-medium text-zinc-300">{{ $this->selectedLocation->address }}</p>
                            <p class="mt-0.5 text-sm text-zinc-500">
                                {{ $this->selectedLocation->city }}, {{ $this->selectedLocation->state }} {{ $this->selectedLocation->zip }}
                            </p>
                        </div>
                    </div>

                    {{-- Phone + email --}}
                    <div class="space-y-3">
                        @if ($this->selectedLocation->phone)
                            <a
                                href="tel:{{ $this->selectedLocation->phone }}"
                                class="group flex items-center gap-3 rounded-xl border border-zinc-800 px-4 py-3 transition-colors duration-200 hover:border-zinc-700 hover:bg-zinc-900/60"
                            >
                                <flux:icon.phone class="size-4 shrink-0 text-zinc-500 transition-colors group-hover:text-amber-400" />
                                <span class="text-sm text-zinc-400 transition-colors group-hover:text-white">{{ $this->selectedLocation->phone }}</span>
                            </a>
                        @endif

                        @if ($this->selectedLocation->email)
                            <a
                                href="mailto:{{ $this->selectedLocation->email }}"
                                class="group flex items-center gap-3 rounded-xl border border-zinc-800 px-4 py-3 transition-colors duration-200 hover:border-zinc-700 hover:bg-zinc-900/60"
                            >
                                <flux:icon.envelope class="size-4 shrink-0 text-zinc-500 transition-colors group-hover:text-amber-400" />
                                <span class="text-sm text-zinc-400 transition-colors group-hover:text-white">{{ $this->selectedLocation->email }}</span>
                            </a>
                        @endif
                    </div>

                    {{-- Hours --}}
                    @if ($this->selectedLocation->hours->isNotEmpty())
                        <div>
                            <h3 class="mb-4 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-zinc-500">
                                <flux:icon.clock class="size-3.5" />
                                Hours of Operation
                            </h3>
                            <dl class="space-y-1">
                                @foreach ($this->selectedLocation->hours as $hour)
                                    @php $isToday = $hour->day_of_week === $this->todayDayOfWeek; @endphp
                                    <div @class([
                                        'flex items-center justify-between rounded-lg px-3 py-2 text-sm transition-colors',
                                        'bg-amber-900/30 ring-1 ring-amber-800/40' => $isToday,
                                        'hover:bg-zinc-900/40' => ! $isToday,
                                    ])>
                                        <dt @class([
                                            'font-semibold text-amber-300' => $isToday,
                                            'text-zinc-400' => ! $isToday,
                                        ])>
                                            {{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$hour->day_of_week] }}
                                            @if ($isToday)
                                                <span class="ml-1.5 text-[10px] font-normal uppercase tracking-wide text-amber-500/70">Today</span>
                                            @endif
                                        </dt>
                                        <dd @class([
                                            'font-semibold text-amber-300' => $isToday,
                                            'text-zinc-500' => ! $isToday,
                                        ])>
                                            @if ($hour->closed)
                                                Closed
                                            @else
                                                {{ \Carbon\Carbon::parse($hour->opens_at)->format('g:i A') }}–{{ \Carbon\Carbon::parse($hour->closes_at)->format('g:i A') }}
                                            @endif
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif

                    {{-- Google Maps embed --}}
                    @if ($this->selectedLocation->maps_embed_url)
                        <div class="aspect-video w-full overflow-hidden rounded-2xl ring-1 ring-zinc-800 shadow-xl shadow-black/30">
                            <iframe
                                src="{{ $this->selectedLocation->maps_embed_url }}"
                                width="100%"
                                height="100%"
                                style="border: 0"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    @endif

                @endif
            </div>

            {{-- ===== RIGHT: Form card ===== --}}
            <div>
                @if ($this->submitted)
                    {{-- Success state --}}
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-10 text-center shadow-xl shadow-black/30">
                        <div class="mx-auto mb-6 flex size-16 items-center justify-center rounded-full bg-amber-600/15 ring-1 ring-amber-600/30">
                            <flux:icon.check class="size-8 text-amber-400" />
                        </div>
                        <h2 class="text-2xl font-semibold text-white">
                            @if ($this->successName)
                                Thank you, {{ $this->successName }}!
                            @else
                                Message sent!
                            @endif
                        </h2>
                        <p class="mt-3 leading-relaxed text-sm text-zinc-400">
                            We've received your message and will be in touch shortly.<br>
                            We look forward to welcoming you soon.
                        </p>
                        <button
                            wire:click="$set('submitted', false)"
                            class="mt-8 text-sm font-medium text-amber-500 underline-offset-4 transition-colors hover:text-amber-400 hover:underline"
                        >
                            Send another message
                        </button>
                    </div>
                @else
                    {{-- Form card --}}
                    <div class="overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 shadow-xl shadow-black/30">

                        {{-- Amber gradient top accent bar --}}
                        <div class="h-1 w-full bg-gradient-to-r from-amber-700 via-amber-500 to-amber-700"></div>

                        <div class="p-8">
                            <h2 class="mb-1 text-lg font-semibold text-white">Send Us a Message</h2>
                            <p class="mb-8 text-sm text-zinc-500">We'll get back to you within one business day.</p>

                            <form wire:submit="submit" class="space-y-5">
                                <flux:field>
                                    <flux:label class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Name</flux:label>
                                    <flux:input
                                        wire:model="name"
                                        type="text"
                                        required
                                        autocomplete="name"
                                        placeholder="Your full name"
                                    />
                                    <flux:error name="name" />
                                </flux:field>

                                <flux:field>
                                    <flux:label class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Email</flux:label>
                                    <flux:input
                                        wire:model="email"
                                        type="email"
                                        required
                                        autocomplete="email"
                                        placeholder="you@example.com"
                                    />
                                    <flux:error name="email" />
                                </flux:field>

                                <flux:field>
                                    <flux:label class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Subject</flux:label>
                                    <flux:input
                                        wire:model="subject"
                                        type="text"
                                        placeholder="How can we help?"
                                    />
                                    <flux:error name="subject" />
                                </flux:field>

                                <flux:field>
                                    <flux:label class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Message</flux:label>
                                    <flux:textarea
                                        wire:model="message"
                                        rows="5"
                                        placeholder="Tell us anything — reservations, events, feedback..."
                                    />
                                    <flux:error name="message" />
                                </flux:field>

                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="mt-2 w-full rounded-xl bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-amber-900/30 transition-colors duration-200 hover:bg-amber-500 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <span wire:loading.remove>Send Message</span>
                                    <span wire:loading class="flex items-center justify-center gap-2">
                                        <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Sending…
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </section>
</div>
