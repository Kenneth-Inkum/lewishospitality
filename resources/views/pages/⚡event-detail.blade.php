<?php

use App\Models\Event;
use App\Models\EventRsvp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.public')] class extends Component {
    public Event $event;

    public string $rsvpName = '';
    public string $rsvpEmail = '';
    public int $rsvpPartySize = 1;
    public bool $rsvpSubmitted = false;

    public function mount(string $slug): void
    {
        $this->event = Event::query()
            ->published()
            ->where('slug', $slug)
            ->with('location')
            ->firstOrFail();
    }

    public function submitRsvp(): void
    {
        if (! $this->event->rsvp_enabled) {
            return;
        }

        $validated = $this->validate([
            'rsvpName' => ['required', 'string', 'max:255'],
            'rsvpEmail' => ['required', 'email', 'max:255'],
            'rsvpPartySize' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        EventRsvp::create([
            'event_id' => $this->event->id,
            'name' => $validated['rsvpName'],
            'email' => $validated['rsvpEmail'],
            'party_size' => $validated['rsvpPartySize'],
        ]);

        $this->rsvpSubmitted = true;
        $this->reset(['rsvpName', 'rsvpEmail', 'rsvpPartySize']);
    }
};
?>

<div>
    {{-- Hero Section --}}
    <section class="relative flex min-h-[400px] items-center overflow-hidden bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-800">
        <div class="pointer-events-none absolute -right-24 -top-24 size-[480px] rounded-full border border-zinc-700/20 opacity-40"></div>
        <div class="pointer-events-none absolute -right-12 -top-12 size-[320px] rounded-full border border-zinc-700/20 opacity-30"></div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-amber-600/50 to-transparent"></div>

        <div class="relative mx-auto w-full max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <div class="mb-4 flex items-center gap-3 text-sm text-zinc-400">
                    <a href="{{ route('events') }}" wire:navigate class="transition-colors hover:text-amber-400">Events</a>
                    <flux:icon.chevron-right class="size-4" />
                    <span class="text-zinc-500">{{ $event->starts_at->format('F j, Y') }}</span>
                </div>
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">{{ $event->title }}</h1>
                <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-zinc-400">
                    <div class="flex items-center gap-2">
                        <flux:icon.calendar class="size-4 text-amber-500" />
                        <span>{{ $event->starts_at->format('F j, Y • g:i A') }}</span>
                    </div>
                    @if ($event->location)
                        <div class="flex items-center gap-2">
                            <flux:icon.map-pin class="size-4 text-amber-500" />
                            <span>{{ $event->location->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-[1fr_380px]">
            {{-- Main Content --}}
            <div class="space-y-8">
                {{-- Event Image --}}
                @if ($event->hasMedia('images'))
                    <div class="overflow-hidden rounded-2xl border border-zinc-800 shadow-xl shadow-black/30">
                        <img 
                            src="{{ $event->getFirstMediaUrl('images') }}" 
                            alt="{{ $event->title }}"
                            class="w-full object-cover"
                        />
                    </div>
                @endif

                {{-- Event Description --}}
                <div class="prose prose-invert prose-zinc max-w-none">
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-8 shadow-xl shadow-black/30">
                        {!! $event->description !!}
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- RSVP Card --}}
                @if ($event->rsvp_enabled)
                    <div class="overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 shadow-xl shadow-black/30">
                        <div class="h-1 bg-gradient-to-r from-amber-700 via-amber-500 to-amber-700"></div>
                        <div class="p-6">
                            @if ($rsvpSubmitted)
                                <div class="text-center">
                                    <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-amber-600/15 ring-1 ring-amber-600/30">
                                        <flux:icon.check class="size-8 text-amber-400" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-white">You're on the list!</h3>
                                    <p class="mt-2 text-sm text-zinc-400">We've sent a confirmation to your email. See you at the event!</p>
                                </div>
                            @else
                                <h3 class="mb-1 text-lg font-semibold text-white">RSVP</h3>
                                <p class="mb-6 text-sm text-zinc-500">Reserve your spot for this event.</p>

                                <form wire:submit="submitRsvp" class="space-y-4">
                                    <flux:field>
                                        <flux:label class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Name</flux:label>
                                        <flux:input wire:model="rsvpName" type="text" required placeholder="Your full name" />
                                        <flux:error name="rsvpName" />
                                    </flux:field>

                                    <flux:field>
                                        <flux:label class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Email</flux:label>
                                        <flux:input wire:model="rsvpEmail" type="email" required placeholder="you@example.com" />
                                        <flux:error name="rsvpEmail" />
                                    </flux:field>

                                    <flux:field>
                                        <flux:label class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Party Size</flux:label>
                                        <flux:select wire:model="rsvpPartySize" variant="listbox" searchable required>
                                            @for ($i = 1; $i <= 20; $i++)
                                                <flux:select.option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}</flux:select.option>
                                            @endfor
                                        </flux:select>
                                        <flux:error name="rsvpPartySize" />
                                    </flux:field>

                                    <button
                                        type="submit"
                                        wire:loading.attr="disabled"
                                        class="w-full rounded-xl bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-amber-900/30 transition-colors hover:bg-amber-500 disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        <span wire:loading.remove>Confirm RSVP</span>
                                        <span wire:loading class="flex items-center justify-center gap-2">
                                            <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            Sending…
                                        </span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- External Link CTA (for tickets, registration, etc.) --}}
                @if ($event->cta_type === 'external_link' && $event->cta_url)
                    <div class="overflow-hidden rounded-2xl border border-amber-600/30 bg-gradient-to-br from-amber-950/20 to-zinc-900 shadow-xl shadow-black/30">
                        <div class="h-1 bg-gradient-to-r from-amber-700 via-amber-500 to-amber-700"></div>
                        <div class="p-6">
                            <div class="mb-4 flex items-center gap-2">
                                <flux:icon.ticket class="size-5 text-amber-400" />
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-amber-400">Additional Information</h3>
                            </div>
                            <p class="mb-4 text-sm text-zinc-400">
                                @if (str_contains(strtolower($event->cta_label ?? ''), 'ticket'))
                                    Purchase tickets or get more details about this event.
                                @elseif (str_contains(strtolower($event->cta_label ?? ''), 'register'))
                                    Complete your registration for this event.
                                @else
                                    Visit the event page for more information.
                                @endif
                            </p>
                            <a
                                href="{{ $event->cta_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-amber-900/30 transition-colors hover:bg-amber-500"
                            >
                                {{ $event->cta_label ?? 'Learn More' }}
                                <flux:icon.arrow-up-right class="size-4" />
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Event Details --}}
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-400">Event Details</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-500">Date</dt>
                            <dd class="text-sm text-white">{{ $event->starts_at->format('F j, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-500">Time</dt>
                            <dd class="text-sm text-white">{{ $event->starts_at->format('g:i A') }}</dd>
                        </div>
                        @if ($event->ends_at)
                            <div class="flex justify-between">
                                <dt class="text-sm text-zinc-500">Ends</dt>
                                <dd class="text-sm text-white">{{ $event->ends_at->format('F j, Y • g:i A') }}</dd>
                            </div>
                        @endif
                        @if ($event->location)
                            <div class="pt-3 border-t border-zinc-800">
                                <dt class="mb-1 text-sm text-zinc-500">Location</dt>
                                <dd class="text-sm text-white">{{ $event->location->name }}</dd>
                                <dd class="text-xs text-zinc-500 mt-1">{{ $event->location->address }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Back to Events --}}
                <a
                    href="{{ route('events') }}"
                    wire:navigate
                    class="flex items-center gap-2 text-sm text-zinc-400 transition-colors hover:text-amber-400"
                >
                    <flux:icon.arrow-left class="size-4" />
                    Back to all events
                </a>
            </div>
        </div>
    </section>
</div>