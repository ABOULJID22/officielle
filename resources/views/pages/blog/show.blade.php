<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $post->translation()?->title ?? $post->title }} · Offitrade</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

              <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ $siteSettings?->favicon_path ? Storage::url($siteSettings->favicon_path) : asset('favicon.png') }}" />

</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
 @include('layouts.navbar')

  <main class="min-h-screen py-10 sm:py-14">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">

      <!-- Fil d'Ariane -->
      <nav aria-label="{{ __('site.aria.breadcrumb') }}" class="mb-8 mt-14 text-sm text-gray-500 dark:text-gray-400">
        <ol class="flex items-center gap-2">
          <li>
            <a href="{{ route('pages.blog.index') }}" class="link-underline-soft">
              {{ __('site.nav.blog') }}
            </a>
          </li>
          <li class="opacity-60">/</li>
          <li class="line-clamp-1">{{ $post->title }}</li>
        </ol>
      </nav>

      @php
        $shareHeading = __('site.blog.share.facebook');
        $shareHeading = $shareHeading === 'site.blog.share.facebook' ? 'Partager' : $shareHeading;
      @endphp
      <!-- Image principale -->
      @php
        $img = $post->cover_image ? Storage::url($post->cover_image) : asset('images/img1.jpg');
      @endphp
      <figure class="relative overflow-hidden rounded-[2.5rem] shadow-soft ring-1 ring-gray-200/70 dark:ring-gray-800/60 mb-12 group">
  <img src="{{ $img }}" alt="{{ $post->translation()?->title ?? $post->title }}" loading="lazy" decoding="async"
             class="w-full aspect-[21/9] object-cover transition-transform duration-500 group-hover:scale-[1.02]">
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/30 via-black/0 to-transparent"></div>
        @if($post->category)
          <figcaption class="absolute bottom-3 left-3 text-white/90 text-xs md:backdrop-blur-sm px-2 py-1 rounded">
            {{ $post->category->name }}
          </figcaption>
        @endif
      </figure>

      <section class="mb-10 space-y-4">
        <div class="flex flex-wrap items-center gap-3">
          @if($post->category)
            <a href="{{ route('pages.blog.index', ['category' => $post->category->slug]) }}" class="badge-pill">
              {{ $post->category->name }}
            </a>
          @endif

          @if(!empty($post->reading_time))
            <span class="stat-pill">
              {{ trans_choice('site.blog.reading_minutes', (int) $post->reading_time, ['count' => (int) $post->reading_time]) }}
            </span>
          @endif
        </div>

        <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white">
          {{ $post->translation()?->title ?? $post->title }}
        </h2>

        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4 text-primary-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="6 9 12 15 18 9" />
              <path d="M19.5 12a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" />
            </svg>
            <span>{{ __('site.blog.Publishedon') }}</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ $post->published_at->format('F j, Y') }}</span>
          </span>
        </div>
      </section>

      <!-- Contenu -->
      @php
        $content = $post->translation()?->content ?? $post->content;
      @endphp
      @if($content)
        @php
          $renderedContent = \Filament\Forms\Components\RichEditor\RichContentRenderer::make($content)->toHtml();
        @endphp
        <article class="prose prose-slate dark:prose-invert fi-prose max-w-none prose-headings:scroll-mt-24">
          {!! $renderedContent !!}
        </article>
      @else
        <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl p-8 text-center">
          <p class="text-black dark:text-white italic">
            {{ __('site.blog.no_content') }}
          </p>
        </div>
      @endif

      <!-- Tags (activer si relation tags existe) -->
      @isset($post->tags)
        @if($post->tags->count())
          <div class="mt-10 flex flex-wrap gap-2">
            @foreach ($post->tags as $tag)
              <a href="{{ route('pages.blog.index', ['search' => $tag->name]) }}"
                 class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-primary-500 hover:text-white transition-colors dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-primary-400">
                #{{ $tag->name }}
              </a>
            @endforeach
          </div>
        @endif
      @endisset

      <!-- Partage -->
      @php $url = route('pages.blog.show', $post); @endphp
      <div class="mt-12 border-t border-gray-200/60 dark:border-gray-800/70 pt-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
          <a href="{{ route('pages.blog.index') }}" class="link-underline-soft text-sm text-gray-600 dark:text-gray-300">
            ← {{ __('site.nav.blog') }}
          </a>

          <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-5">
            <span class="uppercase tracking-[0.2em] text-xs text-gray-500 dark:text-gray-400">{{ $shareHeading }}</span>
            <div class="flex flex-wrap gap-2 sm:gap-3">
             <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($url) }}&text={{ urlencode($post->title) }}"
                 target="_blank" rel="noopener"
                 class="share-button bg-[#1da1f2] text-white w-full sm:w-auto">
                LinkedIn
              </a>
              <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ urlencode($post->title) }}"
                 target="_blank" rel="noopener"
                 class="share-button bg-[#1da1f2] text-white w-full sm:w-auto">
                X 
              </a>
              <a href="mailto:?subject={{ rawurlencode($post->title) }}&body={{ rawurlencode($url) }}"
                 class="share-button bg-primary-500/10 text-black dark:text-white dark:hover:text-white w-full sm:w-auto">
                {{ __('site.blog.share.email') }}
              </a>
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
                 target="_blank" rel="noopener"
                 class="share-button bg-[#3b5998] text-white w-full sm:w-auto">
                Facebook
              </a>
              <button type="button"
                x-data="{ copied: false }"
                x-on:click="navigator.clipboard.writeText('{{ $url }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                class="share-button bg-gray-100 text-gray-800 hover:bg-primary-500 hover:text-[#4c51bf] dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-primary-500 w-full sm:w-auto">
                {{ __('site.blog.share.copy_link') }}
                <span x-show="copied" x-transition class="ml-2 inline-block text-sm text-green-600 dark:text-green-400" aria-live="polite">✔︎</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation précédent / suivant -->
      <nav class="mt-14 grid gap-4 sm:grid-cols-2">
        @if($prev)
          <a href="{{ route('pages.blog.show', $prev) }}"
             class="group rounded-2xl border border-gray-200/70 dark:border-gray-800/60 p-5 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('site.blog.previous') }}</div>
            <div class="font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-primary-500 dark:group-hover:text-primary-300">
              {{ $prev->title }}
            </div>
          </a>
        @endif

        @if($next)
          <a href="{{ route('pages.blog.show', $next) }}"
             class="group rounded-2xl border border-gray-200/70 dark:border-gray-800/60 p-5 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors text-right">
            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('site.blog.next') }}</div>
            <div class="font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-primary-500 dark:group-hover:text-primary-300">
              {{ $next->title }}
            </div>
          </a>
        @endif
      </nav>

      <!-- Articles récents -->
      @if($recent->count())
        <section class="mt-16">
          <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-900 dark:text-white">{{ __('site.blog.recent') }}</h2>
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($recent as $r)
              @php $rimg = $r->cover_image ? Storage::url($r->cover_image) : asset('images/img1.jpg'); @endphp
              <a href="{{ route('pages.blog.show', $r) }}"
                 class="group bg-white dark:bg-gray-800 rounded-2xl shadow ring-1 ring-gray-200/70 dark:ring-gray-800/60 hover:shadow-lg transition-all overflow-hidden">
                <img src="{{ $rimg }}" loading="lazy" decoding="async" class="w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" alt="{{ $r->title }}">
                <div class="p-4">
                  <h3 class="text-base font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-primary-500 dark:group-hover:text-primary-300">
                    {{ $r->title }}
                  </h3>
                </div>
              </a>
            @endforeach
          </div>
        </section>
      @endif

    </div>
  </main>

  @include('layouts.footer')
</body>
</html>
