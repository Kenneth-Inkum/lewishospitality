<?php

use App\Models\Promotion;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    /** @return Collection<int, Promotion> */
    #[Computed]
    public function promotions(): Collection
    {
        return Promotion::query()
            ->active()
            ->where('show_on_homepage', true)
            ->limit(3)
            ->get();
    }
};
?>

<div>
    @if ($this->promotions->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-12">
            <div class="overflow-hidden rounded-2xl border border-amber-600/30 bg-gradient-to-r from-amber-950/50 via-zinc-900 to-amber-950/50">
                <div class="px-6 py-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-amber-600/20 ring-1 ring-amber-600/40">
                                <flux:icon.sparkles class="size-5 text-amber-400" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-amber-400">Special Offers</h3>
                                <p class="text-xs text-zinc-500">Limited time promotions</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @foreach ($this->promotions as $promotion)
                                <div class="rounded-lg border border-amber-600/20 bg-zinc-950/50 px-4 py-2">
                                    <span class="text-sm font-medium text-white">{{ $promotion->title }}</span>
                                    @if ($promotion->ends_at)
                                        <span class="ml-2 text-xs text-amber-500/70">Ends {{ $promotion->ends_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>