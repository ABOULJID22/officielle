<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $post->title }} · Offitrade</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
 @include('layouts.navbar')

  <main class="min-h-screen py-10 sm:py-14">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">

      <!-- Fil d'Ariane -->
      <nav aria-label="Fil d'Ariane" class="mb-6 text-sm text-gray-500 dark:text-gray-400">
        <ol class="flex items-center gap-2">
          <li>
            <a href="{{ route('pages.blog.index') }}" class="hover:text-gray-900 dark:hover:text-gray-200 underline-offset-4 hover:underline">
              Blog
            </a>
          </li>
          <li class="opacity-60">/</li>
          <li class="line-clamp-1">{{ $post->title }}</li>
        </ol>
      </nav>

      <!-- Header -->
      <header class="mb-8">
        <div class="flex items-center gap-3 mb-3">
          @if($post->category)
            <a href="{{ route('pages.blog.index', ['category' => $post->category->slug]) }}"
               class="inline-flex items-center rounded-full bg-indigo-600/10 text-indigo-700 dark:text-indigo-300 px-3 py-1 text-xs font-semibold ring-1 ring-indigo-600/20">
              {{ $post->category->name }}
            </a>
          @endif
        </div>

        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
          {{ $post->title }}
        </h1>

        <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
          <div class="flex items-center gap-2">
            <img src="{{ asset('images/avatar.jpg') }}" alt="Auteur" class="h-8 w-8 rounded-full object-cover ring-2 ring-white/70 dark:ring-gray-800/70">
            <span>{{ optional($post->author)->name ?? '—' }}</span>
          </div>
          <span class="hidden sm:inline opacity-50">•</span>
          <span>{{ optional($post->published_at)->diffForHumans() ?? '' }}</span>
          @if(!empty($post->reading_time))
            <span class="hidden sm:inline opacity-50">•</span>
            <span>{{ $post->reading_time }} min de lecture</span>
          @endif
        </div>
      </header>

      <!-- Image principale -->
      @php
        $img = $post->cover_image ? Storage::url($post->cover_image) : asset('images/img1.jpg');
      @endphp
      <figure class="relative overflow-hidden rounded-3xl shadow ring-1 ring-gray-200/70 dark:ring-gray-800/60 mb-10 group">
        <img src="{{ $img }}" alt="{{ $post->title }}"
             class="w-full aspect-[21/9] object-cover transition-transform duration-500 group-hover:scale-[1.02]">
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/30 via-black/0 to-transparent"></div>
        @if($post->category)
          <figcaption class="absolute bottom-3 left-3 text-white/90 text-xs backdrop-blur-sm px-2 py-1 rounded">
            {{ $post->category->name }}
          </figcaption>
        @endif
      </figure>

      <!-- Contenu -->
      <article class="prose prose-slate dark:prose-invert max-w-none prose-headings:scroll-mt-24">
        {!! $post->content !!} {{-- si le contenu vient d’un éditeur riche --}}
        {{-- Si contenu brut: remplacer par {!! nl2br(e($post->content)) !!} --}}
      </article>

      <!-- Tags (activer si relation tags existe) -->
      @isset($post->tags)
        @if($post->tags->count())
          <div class="mt-10 flex flex-wrap gap-2">
            @foreach ($post->tags as $tag)
              <a href="{{ route('pages.blog.index', ['search' => $tag->name]) }}"
                 class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-indigo-600 hover:text-white transition-colors dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-indigo-500">
                #{{ $tag->name }}
              </a>
            @endforeach
          </div>
        @endif
      @endisset

      <!-- Partage -->
      @php $url = route('pages.blog.show', $post); @endphp
      <div class="mt-8 flex flex-wrap gap-3">
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
           target="_blank" rel="noopener"
           class="group inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2 shadow hover:shadow-md transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
          Partager Facebook
        </a>
        <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ urlencode($post->title) }}"
           target="_blank" rel="noopener"
           class="group inline-flex items-center gap-2 rounded-xl bg-sky-400 text-white px-4 py-2 shadow hover:shadow-md transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500">
          Partager Twitter
        </a>
        <a href="mailto:?subject={{ rawurlencode($post->title) }}&body={{ rawurlencode($url) }}"
           class="group inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-4 py-2 shadow hover:shadow-md transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500">
          Partager Email
        </a>
        <button type="button"
                x-data
                x-on:click="navigator.clipboard.writeText('{{ $url }}')"
                class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 text-gray-800 px-4 py-2 shadow hover:shadow-md transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:bg-gray-800 dark:text-gray-200">
          Copier le lien
        </button>
      </div>

      <!-- Navigation précédent / suivant -->
      <nav class="mt-14 grid gap-4 sm:grid-cols-2">
        @if($prev)
          <a href="{{ route('pages.blog.show', $prev) }}"
             class="group rounded-2xl border border-gray-200/70 dark:border-gray-800/60 p-5 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Article précédent</div>
            <div class="font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
              {{ $prev->title }}
            </div>
          </a>
        @endif

        @if($next)
          <a href="{{ route('pages.blog.show', $next) }}"
             class="group rounded-2xl border border-gray-200/70 dark:border-gray-800/60 p-5 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors text-right">
            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Article suivant</div>
            <div class="font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
              {{ $next->title }}
            </div>
          </a>
        @endif
      </nav>

      <!-- Articles récents -->
      @if($recent->count())
        <section class="mt-16">
          <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-900 dark:text-white">Articles récents</h2>
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($recent as $r)
              @php $rimg = $r->cover_image ? Storage::url($r->cover_image) : asset('images/img1.jpg'); @endphp
              <a href="{{ route('pages.blog.show', $r) }}"
                 class="group bg-white dark:bg-gray-800 rounded-2xl shadow ring-1 ring-gray-200/70 dark:ring-gray-800/60 hover:shadow-lg transition-all overflow-hidden">
                <img src="{{ $rimg }}" class="w-full h-36 object-cover transition-transform duration-300 group-hover:scale-[1.03]" alt="{{ $r->title }}">
                <div class="p-4">
                  <h3 class="text-base font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
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
