<?php

use App\Models\Setting;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function posts(): array
    {
        // Get Instagram posts from settings (would be populated by scheduled job)
        return Setting::get('instagram.recent_posts', []);
    }

    #[Computed]
    public function instagramHandle(): ?string
    {
        return Setting::get('instagram.handle');
    }
};
?>

<div>
    @if (!empty($this->posts))
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{-- Section Header --}}
                <div class="mb-12 text-center">
                    <div class="mb-4 flex items-center justify-center gap-4">
                        <div class="h-px w-12 bg-gradient-to-r from-transparent to-amber-600"></div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-500">Follow Us</p>
                        <div class="h-px w-12 bg-gradient-to-l from-transparent to-amber-600"></div>
                    </div>
                    <h2 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                        @if ($this->instagramHandle)
                            @{{ $this->instagramHandle }}
                        @else
                            Instagram
                        @endif
                    </h2>
                    <p class="mx-auto mt-3 max-w-2xl text-base text-zinc-400">See what's happening at our restaurants</p>
                </div>

                {{-- Instagram Grid --}}
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                    @foreach (array_slice($this->posts, 0, 6) as $index => $post)
                        <a href="{{ $post['permalink'] ?? '#' }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="group relative aspect-square overflow-hidden rounded-xl ring-1 ring-zinc-800 transition-all duration-300 hover:-translate-y-1 hover:ring-2 hover:ring-amber-600/50 hover:shadow-xl hover:shadow-amber-900/20 animate-in fade-in zoom-in-95 duration-500"
                           style="animation-delay: {{ $index * 100 }}ms"
                        >
                            <img 
                                src="{{ $post['media_url'] ?? $post['thumbnail_url'] ?? '' }}" 
                                alt="{{ $post['caption'] ?? 'Instagram post' }}"
                                class="size-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:brightness-110"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <div class="flex items-center gap-3 text-sm text-white">
                                        @if (isset($post['like_count']))
                                            <div class="flex items-center gap-1">
                                                <flux:icon.heart class="size-4" />
                                                <span>{{ number_format($post['like_count']) }}</span>
                                            </div>
                                        @endif
                                        @if (isset($post['comments_count']))
                                            <div class="flex items-center gap-1">
                                                <flux:icon.chat-bubble-left class="size-4" />
                                                <span>{{ number_format($post['comments_count']) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Follow CTA --}}
                <div class="mt-12 text-center">
                    @if ($this->instagramHandle)
                        <a href="https://instagram.com/{{ $this->instagramHandle }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 rounded-full border border-amber-600/30 bg-amber-600/10 px-6 py-3 text-sm font-semibold text-amber-400 transition-all duration-200 hover:border-amber-600 hover:bg-amber-600/20">
                            <flux:icon.camera class="size-4" />
                            <span>Follow on Instagram</span>
                        </a>
                    @endif
                </div>
            </div>
        </section>
    @endif
</div>
{{-- The only way to do great work is to love what you do. - Steve Jobs --}}