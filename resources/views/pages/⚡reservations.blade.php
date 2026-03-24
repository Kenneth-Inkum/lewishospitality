<?php

use App\Models\Location;
use App\Models\Reservation;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Reservations'), Layout('layouts.public')] class extends Component {
    #[Validate('required|exists:locations,id')]
    public $location_id = '';

    #[Validate('required|date|after_or_equal:today')]
    public $date = '';

    #[Validate('required')]
    public $time = '';

    #[Validate('required|integer|min:1|max:20')]
    public $party_size = 2;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:30')]
    public $phone = '';

    #[Validate('nullable|string|max:1000')]
    public $special_requests = '';

    public bool $submitted = false;

    public function mount(): void
    {
        // Set default date to tomorrow
        $this->date = now()->addDay()->format('Y-m-d');
        
        // Set default location if only one exists
        $locations = $this->locations;
        if ($locations->count() === 1) {
            $this->location_id = $locations->first()->id;
        }
    }

    /** @return Collection<int, Location> */
    #[Computed]
    public function locations(): Collection
    {
        return Location::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }

    public function submit(): void
    {
        $validated = $this->validate();

        Reservation::create([
            ...$validated,
            'status' => 'pending',
            'source' => 'online_form',
        ]);

        $this->submitted = true;
        $this->reset(['location_id', 'date', 'time', 'party_size', 'name', 'email', 'phone', 'special_requests']);
    }

    public function resetForm(): void
    {
        $this->submitted = false;
        $this->mount();
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
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">Reserve a Table</h1>
                <p class="mt-4 text-lg text-zinc-400">Book your dining experience with us</p>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="bg-zinc-950 py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            @if ($submitted)
                {{-- Success Message --}}
                <div class="rounded-2xl border border-green-800 bg-green-950/50 p-8 text-center">
                    <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-green-900/50">
                        <flux:icon.check class="size-8 text-green-400" />
                    </div>
                    <h2 class="mb-2 text-2xl font-semibold text-white">Reservation Submitted!</h2>
                    <p class="mb-6 text-zinc-400">
                        Thank you for your reservation request. We'll send you a confirmation email shortly.
                    </p>
                    <button
                        wire:click="resetForm"
                        class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-900/30 transition-all duration-200 hover:bg-amber-500"
                    >
                        Make Another Reservation
                    </button>
                </div>
            @else
                {{-- Reservation Form --}}
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-8 shadow-xl shadow-black/30">
                    <form wire:submit="submit" class="space-y-6">
                        {{-- Location Selection --}}
                        <flux:field>
                            <flux:label>Location <span class="text-red-500">*</span></flux:label>
                            <flux:select wire:model="location_id" variant="listbox" placeholder="Select a location">
                                @foreach ($this->locations as $location)
                                    <flux:select.option value="{{ $location->id }}">{{ $location->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="location_id" />
                        </flux:field>

                        {{-- Date and Time --}}
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Date <span class="text-red-500">*</span></flux:label>
                                <flux:date-picker wire:model="date" />
                                <flux:error name="date" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Time <span class="text-red-500">*</span></flux:label>
                                <flux:select wire:model="time" variant="listbox" placeholder="Select time">
                                    <flux:select.option value="17:00">5:00 PM</flux:select.option>
                                    <flux:select.option value="17:30">5:30 PM</flux:select.option>
                                    <flux:select.option value="18:00">6:00 PM</flux:select.option>
                                    <flux:select.option value="18:30">6:30 PM</flux:select.option>
                                    <flux:select.option value="19:00">7:00 PM</flux:select.option>
                                    <flux:select.option value="19:30">7:30 PM</flux:select.option>
                                    <flux:select.option value="20:00">8:00 PM</flux:select.option>
                                    <flux:select.option value="20:30">8:30 PM</flux:select.option>
                                    <flux:select.option value="21:00">9:00 PM</flux:select.option>
                                    <flux:select.option value="21:30">9:30 PM</flux:select.option>
                                </flux:select>
                                <flux:error name="time" />
                            </flux:field>
                        </div>

                        {{-- Party Size --}}
                        <flux:field>
                            <flux:label>Party Size <span class="text-red-500">*</span></flux:label>
                            <flux:select wire:model="party_size" variant="listbox">
                                @for ($i = 1; $i <= 12; $i++)
                                    <flux:select.option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}</flux:select.option>
                                @endfor
                                <flux:select.option value="13">13+ Guests (Large Party)</flux:select.option>
                            </flux:select>
                            <flux:error name="party_size" />
                        </flux:field>

                        {{-- Guest Information --}}
                        <div class="border-t border-zinc-800 pt-6">
                            <h3 class="mb-4 text-lg font-semibold text-white">Your Information</h3>

                            <div class="space-y-4">
                                <flux:field>
                                    <flux:label>Name <span class="text-red-500">*</span></flux:label>
                                    <flux:input wire:model="name" placeholder="John Doe" />
                                    <flux:error name="name" />
                                </flux:field>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <flux:field>
                                        <flux:label>Email <span class="text-red-500">*</span></flux:label>
                                        <flux:input wire:model="email" type="email" placeholder="john@example.com" />
                                        <flux:error name="email" />
                                    </flux:field>

                                    <flux:field>
                                        <flux:label>Phone</flux:label>
                                        <flux:input wire:model="phone" type="tel" placeholder="(555) 123-4567" />
                                        <flux:error name="phone" />
                                    </flux:field>
                                </div>
                            </div>
                        </div>

                        {{-- Special Requests --}}
                        <flux:field>
                            <flux:label>Special Requests</flux:label>
                            <flux:textarea 
                                wire:model="special_requests" 
                                rows="4" 
                                placeholder="Dietary restrictions, occasion, seating preferences, etc."
                            />
                            <flux:error name="special_requests" />
                        </flux:field>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-between border-t border-zinc-800 pt-6">
                            <p class="text-sm text-zinc-500">
                                <span class="text-red-500">*</span> Required fields
                            </p>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-900/30 transition-all duration-200 hover:bg-amber-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <flux:icon.calendar class="size-4" wire:loading.remove />
                                <svg wire:loading class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove>Submit Reservation</span>
                                <span wire:loading>Submitting...</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Info Box --}}
                <div class="mt-6 rounded-lg border border-zinc-800 bg-zinc-900/50 p-6">
                    <div class="flex gap-4">
                        <div class="shrink-0">
                            <flux:icon.information-circle class="size-6 text-amber-500" />
                        </div>
                        <div class="text-sm text-zinc-400">
                            <p class="mb-2 font-semibold text-white">Reservation Policy</p>
                            <ul class="space-y-1">
                                <li>• Reservations are confirmed via email within 24 hours</li>
                                <li>• Please arrive within 15 minutes of your reservation time</li>
                                <li>• For parties of 13 or more, please call us directly</li>
                                <li>• Cancellations must be made at least 24 hours in advance</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>