<?php

use App\Models\Reservation;
use App\Models\Event;
use App\Models\ContactSubmission;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')] class extends Component {
    #[Computed]
    public function todayReservations(): int
    {
        return Reservation::query()
            ->where('date', today())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();
    }

    #[Computed]
    public function pendingReservations(): int
    {
        return Reservation::query()
            ->where('status', 'pending')
            ->where('date', '>=', today())
            ->count();
    }

    #[Computed]
    public function upcomingEvents(): int
    {
        return Event::query()
            ->where('published', true)
            ->where('starts_at', '>=', now())
            ->count();
    }

    #[Computed]
    public function unreadContacts(): int
    {
        return ContactSubmission::query()
            ->whereNull('read_at')
            ->count();
    }

    #[Computed]
    public function recentReservations(): Collection
    {
        return Reservation::query()
            ->with('location')
            ->latest('date')
            ->latest('time')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function todayReservationsChart(): array
    {
        // Get reservations count for the last 12 days
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $count = Reservation::query()
                ->whereDate('date', $date)
                ->count();
            $data[] = $count;
        }
        return $data;
    }

    #[Computed]
    public function pendingReservationsChart(): array
    {
        // Get pending reservations count for the last 12 days
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $count = Reservation::query()
                ->where('status', 'pending')
                ->whereDate('created_at', $date)
                ->count();
            $data[] = $count;
        }
        return $data;
    }

    #[Computed]
    public function upcomingEventsChart(): array
    {
        // Get events count for the last 12 days
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $count = Event::query()
                ->where('published', true)
                ->whereDate('created_at', $date)
                ->count();
            $data[] = $count;
        }
        return $data;
    }

    #[Computed]
    public function unreadContactsChart(): array
    {
        // Get unread contacts count for the last 12 days
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $count = ContactSubmission::query()
                ->whereNull('read_at')
                ->whereDate('created_at', $date)
                ->count();
            $data[] = $count;
        }
        return $data;
    }
};
?>

<div class="space-y-6">
    {{-- Page Header --}}
    <div>
        <flux:heading size="xl">Dashboard</flux:heading>
        <flux:subheading>Welcome back! Here's what's happening today.</flux:subheading>
    </div>

    {{-- Stats Grid --}}
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Today's Reservations --}}
        <flux:card class="flex flex-col overflow-hidden">
            <flux:subheading>Today's Reservations</flux:subheading>
            <flux:heading size="xl" class="mt-2 tabular-nums">{{ $this->todayReservations }}</flux:heading>
            
            <flux:chart class="-mx-8 -mb-8 mt-auto h-[3rem]" :value="$this->todayReservationsChart">
                <flux:chart.svg gutter="0">
                    <flux:chart.line class="text-amber-200 dark:text-amber-400" />
                    <flux:chart.area class="text-amber-100 dark:text-amber-400/30" />
                </flux:chart.svg>
            </flux:chart>
        </flux:card>

        {{-- Pending Reservations --}}
        <flux:card class="flex flex-col overflow-hidden">
            <div>
                <flux:subheading>Pending Reservations</flux:subheading>
                <flux:heading size="xl" class="mt-2 tabular-nums">{{ $this->pendingReservations }}</flux:heading>
                
                @if($this->pendingReservations > 0)
                    <flux:button size="sm" variant="primary" :href="route('admin.reservations.index')" wire:navigate class="mt-4">
                        Review pending
                    </flux:button>
                @endif
            </div>
            
            <flux:chart class="-mx-8 -mb-8 mt-auto h-[3rem]" :value="$this->pendingReservationsChart">
                <flux:chart.svg gutter="0">
                    <flux:chart.line class="text-blue-200 dark:text-blue-400" />
                    <flux:chart.area class="text-blue-100 dark:text-blue-400/30" />
                </flux:chart.svg>
            </flux:chart>
        </flux:card>

        {{-- Upcoming Events --}}
        <flux:card class="flex flex-col overflow-hidden">
            <flux:subheading>Upcoming Events</flux:subheading>
            <flux:heading size="xl" class="mt-2 tabular-nums">{{ $this->upcomingEvents }}</flux:heading>
            
            <flux:chart class="-mx-8 -mb-8 mt-auto h-[3rem]" :value="$this->upcomingEventsChart">
                <flux:chart.svg gutter="0">
                    <flux:chart.line class="text-purple-200 dark:text-purple-400" />
                    <flux:chart.area class="text-purple-100 dark:text-purple-400/30" />
                </flux:chart.svg>
            </flux:chart>
        </flux:card>

        {{-- Unread Messages --}}
        <flux:card class="flex flex-col overflow-hidden">
            <flux:subheading>Unread Messages</flux:subheading>
            <flux:heading size="xl" class="mt-2 tabular-nums">{{ $this->unreadContacts }}</flux:heading>
            
            <flux:chart class="-mx-8 -mb-8 mt-auto h-[3rem]" :value="$this->unreadContactsChart">
                <flux:chart.svg gutter="0">
                    <flux:chart.line class="text-green-200 dark:text-green-400" />
                    <flux:chart.area class="text-green-100 dark:text-green-400/30" />
                </flux:chart.svg>
            </flux:chart>
        </flux:card>
    </div>

    {{-- Recent Reservations --}}
    <flux:card>
        <div class="mb-4 flex items-center justify-between">
            <flux:heading size="lg">Recent Reservations</flux:heading>
            <flux:button size="sm" variant="ghost" :href="route('admin.reservations.index')" wire:navigate>
                View all
            </flux:button>
        </div>

        @if($this->recentReservations->isEmpty())
            <div class="py-12 text-center">
                <flux:icon.calendar class="mx-auto size-12 text-zinc-400" />
                <flux:subheading class="mt-4">No reservations yet</flux:subheading>
            </div>
        @else
            <flux:table class="[&_td]:px-6 [&_td]:py-4 [&_th]:px-6 [&_th]:py-3">
                <flux:table.columns>
                    <flux:table.column>Guest</flux:table.column>
                    <flux:table.column>Location</flux:table.column>
                    <flux:table.column>Date & Time</flux:table.column>
                    <flux:table.column>Party Size</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($this->recentReservations as $reservation)
                        <flux:table.row :key="$reservation->id">
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $reservation->name }}</span>
                                    <span class="text-sm text-zinc-500">{{ $reservation->email }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>{{ $reservation->location->name }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span>{{ $reservation->date->format('M d, Y') }}</span>
                                    <span class="text-sm text-zinc-500">{{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>{{ $reservation->party_size }} {{ $reservation->party_size === 1 ? 'guest' : 'guests' }}</flux:table.cell>
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
                                >
                                    {{ ucfirst($reservation->status) }}
                                </flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif
    </flux:card>

    {{-- Quick Actions --}}
    <flux:card>
        <flux:heading size="lg" class="mb-4">Quick Actions</flux:heading>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <flux:button variant="primary" :href="route('admin.reservations.index')" wire:navigate>
                <flux:icon.calendar class="size-4" />
                Manage Reservations
            </flux:button>
            
            <flux:button variant="outline" href="#">
                <flux:icon.document-text class="size-4" />
                Edit Menu
            </flux:button>
            
            <flux:button variant="outline" href="#">
                <flux:icon.calendar-days class="size-4" />
                Manage Events
            </flux:button>
            
            <flux:button variant="outline" :href="route('home')" target="_blank">
                <flux:icon.globe-alt class="size-4" />
                View Website
            </flux:button>
        </div>
    </flux:card>
</div>
