{{-- resources/views/pages/blog/index.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Offitrade - {{ __('site.nav.blog') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ $siteSettings?->favicon_path ? Storage::url($siteSettings->favicon_path) : asset('favicon.png') }}" />
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">

  @include('layouts.navbar')
<!--  <main class="flex-grow py-16 sm:py-24 bg-gradient-to-b from-primary-50/60 via-white to-white dark:from-slate-950/40 dark:via-slate-900 dark:to-slate-900"> -->
  <main class="flex-grow py-16 sm:py-24 bg-gray-50 dark:bg-gray-900">
    @php
      $activeCat = request('category');
      $activeSort = request('sort', 'recent');
      $search = request('search', '');
      $blogTitle = __('site.blog.title') === 'site.blog.title' ? 'Nos publications' : __('site.blog.title');
      $blogSubtitle = __('site.blog.subtitle') === 'site.blog.subtitle' ? 'Inspiration, conseils & actualités Offitrade.' : __('site.blog.subtitle');
    @endphp

    <!-- Filtre & Titre -->
    <section class="border-b border-gray-200 dark:border-gray-800 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl rounded-3xl mb-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex flex-col gap-6">
          <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <p class="text-xs uppercase tracking-widest text-[#4f6ba3] dark:text-blue-400 font-bold mb-2">{{ __('site.nav.blog') }}</p>
              <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $blogTitle }}</h1>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-lg">{{ $blogSubtitle }}</p>
            </div>

          <form method="GET" action="{{ route('pages.blog.index') }}" class="search-bar flex flex-col sm:flex-row gap-3 w-full sm:w-auto max-w-md   rounded-3xl shadow-md dark:shadow-lg p-4 sm:p-3 transition-colors duration-300">
              <input type="hidden" name="category" value="{{ $activeCat }}" />

              <input 
                type="search" 
                name="search" 
                value="{{ $search }}" 
                placeholder="{{ __('site.blog.search_placeholder') }}" 
                aria-label="Recherche blog"
                class="search-input flex-grow rounded-2xl border border-gray-300 bg-gray-50 px-5 py-3 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-gray-700 transition duration-300 hover:bg-gray-100 dark:hover:bg-gray-700"
              />

              <button type="submit" class="bg-gradient-to-r from-[#4f6ba3] to-[#5b7db5] rounded-2xl px-6 py-3 font-semibold text-white shadow-md hover:from-primary-700 hover:to-primary-600 active:scale-[0.98] transition-transform duration-150 whitespace-nowrap">
                {{ __('site.blog.apply') }}
              </button>
            </form>
          

          </div>

          <!-- Catégories -->
          <div class="overflow-x-auto pb-2">
             <div class="flex items-center gap-3 min-w-max overflow-x-auto scrollbar-thin scrollbar-thumb-primary-300 scrollbar-thumb-rounded">
              <a href="{{ route('pages.blog.index') }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ !$activeCat ? 'bg-[#4f6ba3] text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                {{ __('site.blog.all') }}
              </a>
              @foreach ($categories as $cat)
                <a href="{{ route('pages.blog.index', array_filter(['category' => $cat->slug, 'sort' => $activeSort, 'search' => $search])) }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ $activeCat === $cat->slug ? 'bg-[#4f6ba3] text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                  {{ $cat->name }}
                </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Articles -->
    <section class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 grid gap-10 sm:grid-cols-2 xl:grid-cols-3">
         @forelse ($posts as $post)
          @php
            $img = $post->cover_image ? Storage::url($post->cover_image) : asset('images/img1.jpg');
          @endphp
          <article class="group flex flex-col h-full overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-sm hover:shadow-xl  dark:border-gray-700 transition-all duration-300 hover:-translate-y-1 backdrop-blur-md rounded-2xl  border-l-4 border-[#4f6ba3] transition-transform duration-300 hover:-translate-y-1 hover:shadow-strong">
            {{-- Image Container --}}
            <div class="relative overflow-hidden aspect-[16/9] w-full rounded-2xl">
              <img src="{{ $img }}" 
                   alt="{{ $post->translation()?->title ?? $post->title }}" 
                   loading="lazy" 
                   decoding="async" 
                   class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
              @if($post->category)
                <!-- <span class="absolute top-4 left-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/90 dark:bg-gray-900/90 text-gray-800 dark:text-gray-100 backdrop-blur-sm shadow-sm">
                  {{ $post->category->name }}
                </span> -->
                <span class="absolute top-5 left-5 badge-pill badge-light">
                  {{ $post->category->name }}
                </span>
              @endif
            </div>

            {{-- Content --}}
            <div class="flex flex-1 flex-col p-5 gap-3">
              <div class="space-y-2 mb-1">
                <h3 class="text-lg font-bold tracking-tight text-[#4f6ba3] dark:text-white transition-colors group-hover:text-primary-500 line-clamp-2 dark:group-hover:text-primary-300">
                  <a class="hover:underline text-[#4f6ba3]" href="{{ route('pages.blog.show', $post) }}">{{ $post->translation()?->title ?? $post->title }}</a>
                </h3>
                @php
                  $contentPreviewSource = $post->translation()?->content ?? $post->content ?? $post->excerpt ?? '';
                  $preview = $contentPreviewSource ? \Illuminate\Support\Str::limit(strip_tags($contentPreviewSource), 100) : null;
                @endphp
                @if($preview)
                  <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3 leading-relaxed">{{ $preview }}</p>
                @endif
              </div>

              {{-- Footer Meta --}}
              <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500">
                <div class="flex items-center gap-2">
                   <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                   <span>{{ optional($post->published_at)->format('d M, Y') }}</span>
                </div>
                
                <!-- <a href="{{ route('pages.blog.show', $post) }}" class="flex items-center gap-1 font-medium text-[#4f6ba3] hover:text-[#3d5280] transition-colors">
                  {{ __('site.blog.read_more') }}
                  <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a> -->
                <a href="{{ route('pages.blog.show', $post) }}" class="inline-flex items-center gap-2 font-semibold text-[#4f6ba3] dark:text-primary-300">
                  {{ __('site.blog.read_more') }}
                  <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M8 5h13" /><path d="M8 12h13" /><path d="M8 19h13" /><path d="M5 5l0 0" /><path d="M5 12l0 0" /><path d="M5 19l0 0" />
                  </svg>
                </a>
              </div>
            </div>
          </article>
        @empty
          <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-16">
            {{ __('site.blog.empty') }}
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-10">
        {{ $posts->onEachSide(1)->links() }}
      </div>
    </section>
  </main>

  @include('layouts.footer')

</body>
</html>
