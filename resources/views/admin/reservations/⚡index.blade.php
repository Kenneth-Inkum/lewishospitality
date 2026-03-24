<?php

use App\Models\Reservation;
use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Reservations')] class extends Component {
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = 'all';

    #[Url]
    public string $location = 'all';

    #[Url]
    public string $date = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingLocation(): void
    {
        $this->resetPage();
    }

    public function updatingDate(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function locations(): Collection
    {
        return Location::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function reservations()
    {
        return Reservation::query()
            ->with('location')
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->status !== 'all', fn(Builder $q) => $q->where('status', $this->status))
            ->when($this->location !== 'all', fn(Builder $q) => $q->where('location_id', $this->location))
            ->when($this->date, fn(Builder $q) => $q->whereDate('date', $this->date))
            ->latest('date')
            ->latest('time')
            ->paginate(15);
    }

    public function updateStatus(int $reservationId, string $status): void
    {
        Reservation::find($reservationId)->update(['status' => $status]);
        
        unset($this->reservations);
    }

    public function deleteReservation(int $reservationId): void
    {
        Reservation::find($reservationId)->delete();
        
        unset($this->reservations);
    }
};
?>

<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Reservations</flux:heading>
            <flux:subheading>Manage all restaurant reservations</flux:subheading>
        </div>
    </div>

    {{-- Filters --}}
    <flux:card>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Search --}}
            <flux:field>
                <flux:label>Search</flux:label>
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Name, email, or phone..." />
            </flux:field>

            {{-- Status Filter --}}
            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model.live="status" variant="listbox">
                    <flux:select.option value="all">All Statuses</flux:select.option>
                    <flux:select.option value="pending">Pending</flux:select.option>
                    <flux:select.option value="confirmed">Confirmed</flux:select.option>
                    <flux:select.option value="seated">Seated</flux:select.option>
                    <flux:select.option value="completed">Completed</flux:select.option>
                    <flux:select.option value="cancelled">Cancelled</flux:select.option>
                    <flux:select.option value="no_show">No Show</flux:select.option>
                </flux:select>
            </flux:field>

            {{-- Location Filter --}}
            <flux:field>
                <flux:label>Location</flux:label>
                <flux:select wire:model.live="location" variant="listbox">
                    <flux:select.option value="all">All Locations</flux:select.option>
                    @foreach($this->locations as $loc)
                        <flux:select.option value="{{ $loc->id }}">{{ $loc->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            {{-- Date Filter --}}
            <flux:field>
                <flux:label>Date</flux:label>
                <flux:input wire:model.live="date" type="date" />
            </flux:field>
        </div>

        @if($search || $status !== 'all' || $location !== 'all' || $date)
            <div class="mt-4 flex items-center gap-2">
                <flux:text>Active filters:</flux:text>
                @if($search)
                    <flux:badge>Search: {{ $search }}</flux:badge>
                @endif
                @if($status !== 'all')
                    <flux:badge>Status: {{ ucfirst($status) }}</flux:badge>
                @endif
                @if($location !== 'all')
                    <flux:badge>Location: {{ $this->locations->find($location)->name }}</flux:badge>
                @endif
                @if($date)
                    <flux:badge>Date: {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</flux:badge>
                @endif
                <flux:button size="sm" variant="ghost" wire:click="$set('search', ''); $set('status', 'all'); $set('location', 'all'); $set('date', '')">
                    Clear all
                </flux:button>
            </div>
        @endif
    </flux:card>

    {{-- Reservations Table --}}
    <flux:card>
        @if($this->reservations->isEmpty())
            <div class="py-12 text-center">
                <flux:icon.calendar class="mx-auto size-12 text-zinc-400" />
                <flux:heading size="lg" class="mt-4">No reservations found</flux:heading>
                <flux:subheading class="mt-2">Try adjusting your filters</flux:subheading>
            </div>
        @else
            <flux:table :paginate="$this->reservations" class="[&_td]:px-6 [&_td]:py-4 [&_th]:px-6 [&_th]:py-3">
                <flux:table.columns>
                    <flux:table.column>Guest</flux:table.column>
                    <flux:table.column>Location</flux:table.column>
                    <flux:table.column>Date & Time</flux:table.column>
                    <flux:table.column>Party</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Source</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($this->reservations as $reservation)
                        <flux:table.row :key="$reservation->id">
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $reservation->name }}</span>
                                    <span class="text-sm text-zinc-500">{{ $reservation->email }}</span>
                                    @if($reservation->phone)
                                        <span class="text-sm text-zinc-500">{{ $reservation->phone }}</span>
                                    @endif
                                </div>
                            </flux:table.cell>
                            
                            <flux:table.cell>{{ $reservation->location->name }}</flux:table.cell>
                            
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $reservation->date->format('M d, Y') }}</span>
                                    <span class="text-sm text-zinc-500">{{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}</span>
                                </div>
                            </flux:table.cell>
                            
                            <flux:table.cell>{{ $reservation->party_size }}</flux:table.cell>
                            
                            <flux:table.cell>
                                <flux:badge 
                                    :color="match($reservation->status) {
                                        'confirmed' => 'green',
                                        'pending' => 'yellow',
                                        'seated' => 'blue',
                                        'completed' => 'zinc',
                                        'cancelled', 'no_show' => 'red',
                                        default => 'zinc'
                                    }"
                                    size="sm"
                                    inset="top bottom"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                </flux:badge>
                            </flux:table.cell>
                            
                            <flux:table.cell>
                                <span class="text-sm text-zinc-500">{{ ucfirst(str_replace('_', ' ', $reservation->source)) }}</span>
                            </flux:table.cell>
                            
                            <flux:table.cell>
                                <flux:dropdown position="bottom" align="end">
                                    <flux:button size="sm" variant="ghost" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                                    
                                    <flux:menu>
                                        <flux:menu.item icon="eye" wire:click="$dispatch('show-reservation', { id: {{ $reservation->id }} })">
                                            View Details
                                        </flux:menu.item>
                                        
                                        <flux:menu.separator />
                                        
                                        <flux:menu.submenu heading="Change Status">
                                            <flux:menu.item wire:click="updateStatus({{ $reservation->id }}, 'pending')">
                                                Pending
                                            </flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $reservation->id }}, 'confirmed')">
                                                Confirmed
                                            </flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $reservation->id }}, 'seated')">
                                                Seated
                                            </flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $reservation->id }}, 'completed')">
                                                Completed
                                            </flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $reservation->id }}, 'cancelled')">
                                                Cancelled
                                            </flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $reservation->id }}, 'no_show')">
                                                No Show
                                            </flux:menu.item>
                                        </flux:menu.submenu>
                                        
                                        <flux:menu.separator />
                                        
                                        <flux:menu.item 
                                            variant="danger" 
                                            icon="trash"
                                            wire:click="deleteReservation({{ $reservation->id }})"
                                            wire:confirm="Are you sure you want to delete this reservation?"
                                        >
                                            Delete
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif
    </flux:card>
</div>
