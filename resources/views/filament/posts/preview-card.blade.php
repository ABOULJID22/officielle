<div class="rounded-xl border bg-white shadow-sm overflow-hidden">
    @if($coverUrl)
        <img src="{{ $coverUrl }}" alt="cover" class="w-full h-40 object-cover">
    @endif
    <div class="p-4 space-y-2">
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span class="inline-flex items-center gap-1">
                <span class="w-2 h-2 rounded-full {{ match($status){
                    'published' => 'bg-green-500',
                    'scheduled' => 'bg-yellow-500',
                    default => 'bg-gray-400'
                } }}"></span>
                {{ ucfirst($status ?? 'draft') }}
            </span>
            @if($publishedAt)
                <span>{{ \Illuminate\Support\Carbon::parse($publishedAt)->format('M d, Y') }}</span>
            @endif
        </div>
        <h3 class="text-lg font-semibold">{{ $title ?: 'Sans titre' }}</h3>
        @if($readingTime)
            <div class="text-xs text-gray-500">~ {{ $readingTime }} min read</div>
        @endif
    <p class="text-sm text-gray-700 line-clamp-4">{{ \Illuminate\Support\Str::limit(strip_tags($content ?? ''), 300) }}</p>
        @if($slug)
            <div class="pt-2 text-xs text-gray-500">Slug: <span class="font-mono">/{{ $slug }}</span></div>
        @endif
    </div>
</div>
