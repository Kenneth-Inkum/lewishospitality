<?php

use App\Models\MenuCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component
{
    public function download()
    {
        $categories = $this->getMenuCategories();

        $pdf = Pdf::loadView('pdf.menu', ['categories' => $categories])
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'lewis-hospitality-menu.pdf');
    }

    /** @return Collection<int, MenuCategory> */
    private function getMenuCategories(): Collection
    {
        return MenuCategory::query()
            ->where('active', true)
            ->orderBy('sort_order')
            ->with(['items' => function ($query) {
                $query->where('active', true)
                    ->where(function ($q) {
                        $q->where('available_always', true)
                            ->orWhere(function ($q2) {
                                $q2->where('available_from', '<=', now())
                                    ->where('available_until', '>=', now());
                            });
                    })
                    ->orderBy('sort_order');
            }])
            ->get()
            ->filter(fn($category) => $category->items->isNotEmpty());
    }
};
?>

<button
    wire:click="download"
    wire:loading.attr="disabled"
    class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-amber-900/30 transition-all duration-200 hover:bg-amber-500 disabled:opacity-50 disabled:cursor-not-allowed"
>
    <flux:icon.arrow-down-tray class="size-4" wire:loading.remove wire:target="download" />
    <svg wire:loading wire:target="download" class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <span wire:loading.remove wire:target="download">Download PDF</span>
    <span wire:loading wire:target="download">Generating...</span>
</button>