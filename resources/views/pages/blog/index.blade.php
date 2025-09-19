{{-- resources/views/pages/blog/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Offitrade - {{ __('site.nav.blog') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])

              <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ $siteSettings?->favicon_path ? Storage::url($siteSettings->favicon_path) : asset('favicon.png') }}" />

</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
  @include('layouts.navbar')

  <main class="py-16 sm:py-24">
    <!-- Barre de filtre -->
    @php
      $activeCat = request('category');
      $activeSort = request('sort', 'recent');
      $search = request('search', '');
    @endphp

  <section class="sticky top-0 z-20 border-b border-gray-200/70 dark:border-gray-800/70 bg-white/80 dark:bg-gray-900/70 md:backdrop-blur">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Catégories dynamiques -->
        <div class="flex flex-wrap items-center gap-2">
          <a href="{{ route('pages.blog.index') }}"
             class="px-4 py-2 text-sm font-semibold rounded-full {{ $activeCat ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-200 hover:bg-[#4f6ba3] hover:text-white dark:hover:bg-indigo-500' : 'bg-[#4f6ba3] text-white shadow hover:bg-indigo-500' }}">
            {{ __('site.blog.all') }}
          </a>
          @foreach ($categories as $cat)
            <a href="{{ route('pages.blog.index', array_filter(['category' => $cat->slug, 'sort' => $activeSort, 'search' => $search])) }}"
               class="px-4 py-2 text-sm font-semibold rounded-full {{ $activeCat === $cat->slug ? 'bg-[#4f6ba3] text-white shadow' : 'bg-gray-100 text-gray-900 hover:bg-[#4f6ba3] hover:text-white dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-indigo-500' }}">
              {{ $cat->name }}
            </a>
          @endforeach
        </div>

        <!-- Tri + Recherche -->
        <form method="GET" action="{{ route('pages.blog.index') }}" class="w-full md:w-auto flex items-center gap-3">
          <input type="hidden" name="category" value="{{ $activeCat }}">
          <input type="text" name="search" value="{{ $search }}"
                 placeholder="{{ __('site.blog.search_placeholder') }}"
                 class="flex-1 md:flex-none px-4 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
      
          <button type="submit"
                  class="px-4 py-2 text-sm font-semibold rounded-xl bg-[#4f6ba3] text-white hover:bg-indigo-500">
            {{ __('site.blog.apply') }}
          </button>
        </form>
      </div>
    </section>

    <!-- Grille d’articles -->
    <section class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($posts as $post)
          @php
            $img = $post->cover_image ? Storage::url($post->cover_image) : asset('images/img1.jpg');
          @endphp
          <article class="group overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-md ring-1 ring-gray-200/60 dark:ring-gray-700/50 transition-all duration-300 hover:shadow-xl">
            <div class="relative">
              <img src="{{ $img }}" alt="{{ $post->title }}" loading="lazy" decoding="async" class="w-full aspect-[16/9] object-cover transition-transform duration-300 group-hover:scale-[1.03]">
              <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/0 to-black/0 pointer-events-none"></div>
              @if($post->category)
                <span class="absolute top-4 left-4 inline-flex items-center rounded-full bg-[#4f6ba3] text-white text-xs font-semibold px-3 py-1 ring-1 ring-white/10 md:backdrop-blur">
                  {{ $post->category->name }}
                </span>
              @endif
            </div>
            <div class="p-6">
              <h3 class="text-xl font-semibold tracking-tight mb-2 text-gray-900 dark:text-white transition-colors group-hover:text-[#4f6ba3] dark:group-hover:text-indigo-400">
                <a href="{{ route('pages.blog.show', $post) }}">{{ $post->title }}</a>
              </h3>
              @if($post->excerpt)
                <p class="text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">
                  {{ $post->excerpt }}
                </p>
              @endif
              <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4">
                <span>{{ __('site.blog.Publishedon') }} {{ optional($post->published_at)->format('F j, Y') }}</span>
              </div>
              <a href="{{ route('pages.blog.show', $post) }}" class="mt-4 inline-flex items-center font-semibold text-[#4f6ba3] dark:text-indigo-400 group-hover:underline">
                {{ __('site.blog.read_more') }}
                <svg class="ml-1 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.25 8.25L21 12l-3.75 3.75M21 12H3"/>
                </svg>
              </a>
            </div>
          </article>
        @empty
          <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-16">
            {{ __('site.blog.empty') }}
          </div>
        @endforelse
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-10">
        {{ $posts->onEachSide(1)->links() }}
      </div>
    </section>

  <!-- CTA Newsletter -->
        <!-- <section class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-600 dark:from-indigo-500 dark:to-blue-500 p-8 sm:p-12 text-white shadow-xl">
            <div class="pointer-events-none absolute -inset-1 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.15),transparent_60%)]"></div>
            <div class="relative">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-3 tracking-tight">{{ __('site.blog.newsletter.title') }}</h2>
                <p class="text-lg opacity-90 max-w-2xl">{{ __('site.blog.newsletter.desc') }}</p>
                <form class="mt-8 max-w-xl mx-auto flex flex-col sm:flex-row gap-3">
                <input type="email" placeholder="{{ __('site.blog.newsletter.email_placeholder') }}" required
                        class="flex-1 h-12 rounded-xl px-4 text-gray-900 placeholder-gray-500 bg-white/95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-600">
                <button type="submit"
                        class="h-12 px-6 rounded-xl font-semibold bg-white text-indigo-700 hover:bg-gray-100 active:scale-[0.99] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-600">
                    {{ __('site.blog.newsletter.subscribe') }}
                </button>
                </form>
            </div>
            </div>
        </div>
        </section> -->
  </main>

   @include('layouts.footer') 
</body>
</html>
