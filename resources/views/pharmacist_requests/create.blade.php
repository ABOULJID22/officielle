<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Demande profil Pharmacien — {{ config('app.name', 'Offitrade') }}</title>
  <script>
    (function() {
      try {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const useDark = saved ? (saved === 'dark') : prefersDark;
        document.documentElement.classList.toggle('dark', !!useDark);
      } catch (e) {}
    })();
  </script>
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
  <link rel="icon" type="image/png" href="{{ $siteSettings?->favicon_path ? Storage::url($siteSettings->favicon_path) : asset('favicon.png') }}" />
</head>
<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
  @includeIf('layouts.navbar')

  <div class="max-w-2xl mx-auto py-12 mt-14">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-semibold">Demander un profil Pharmacien</h1>
      
    </div>

    @php
      $submitted = session('status');
      $hasPending = isset($pendingRequest) && $pendingRequest;
      $isApproved = isset($approvedRequest) && $approvedRequest;
      $disabledAttr = $hasPending ? 'disabled' : '';
      $readonlyAttr = $hasPending ? 'readonly' : '';
    @endphp

    @if ($isApproved)
  <div class="mb-6 p-4 rounded border border-green-300 bg-green-50 text-green-900 dark:border-green-700 dark:bg-green-900/30 dark:text-green-200">
        <div class="font-semibold mb-1">Votre demande a été acceptée</div>
        <div>Vous pouvez accéder à votre espace dès maintenant.</div>
        <div class="mt-3">
          <a href="{{ route('filament.admin.pages.dashboard') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Aller à mon espace</a>
        </div>
      </div>
    @elseif ($submitted || $hasPending)
  <div class="mb-6 p-4 rounded border border-yellow-300 bg-yellow-50 text-yellow-900 dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-200">
        <div class="font-semibold mb-1">Votre demande est en cours de validation</div>
        <div>Merci. Nous vous notifierons dès qu’un administrateur aura traité votre demande.</div>
      </div>
    @endif

    @unless ($isApproved)
    <form method="POST" action="{{ route('pharmacist.request.store') }}" class="space-y-4">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Nom du demandeur</label>
          <input type="text" name="applicant_name" value="{{ old('applicant_name', $pendingRequest->applicant_name ?? (auth()->user()->name ?? '')) }}" class="mt-1 w-full rounded border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400" {{ $readonlyAttr }} {{ $disabledAttr }} required>
          @error('applicant_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium">Email du demandeur</label>
          <input type="email" name="applicant_email" value="{{ old('applicant_email', $pendingRequest->applicant_email ?? (auth()->user()->email ?? '')) }}" class="mt-1 w-full rounded border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400" {{ $readonlyAttr }} {{ $disabledAttr }} required>
          @error('applicant_email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Téléphone</label>
          <input type="text" name="phone" value="{{ old('phone', $pendingRequest->phone ?? '') }}" class="mt-1 w-full rounded border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400" {{ $readonlyAttr }} {{ $disabledAttr }}>
          @error('phone')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium">Nom de la pharmacie</label>
          <input type="text" name="pharmacy_name" value="{{ old('pharmacy_name', $pendingRequest->pharmacy_name ?? '') }}" class="mt-1 w-full rounded border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400" {{ $readonlyAttr }} {{ $disabledAttr }} required>
          @error('pharmacy_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium">Adresse de la pharmacie</label>
  <textarea name="pharmacy_address" rows="2" class="mt-1 w-full rounded border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400" {{ $readonlyAttr }} {{ $disabledAttr }}>{{ old('pharmacy_address', $pendingRequest->pharmacy_address ?? '') }}</textarea>
        @error('pharmacy_address')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      

      <div>
        <label class="block text-sm font-medium">Message (optionnel)</label>
  <textarea name="message" rows="4" class="mt-1 w-full rounded border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400" {{ $readonlyAttr }} {{ $disabledAttr }}>{{ old('message', $pendingRequest->message ?? '') }}</textarea>
        @error('message')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      @unless($hasPending)
  <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Envoyer la demande</button>
      @endunless
    </form>
    @endunless

    @if (session('status'))
      <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif
  </div>

  @includeIf('layouts.footer')
  <script>
    (function() {
      var btn = document.getElementById('themeToggle');
      if (!btn) return;
      btn.addEventListener('click', function() {
        var html = document.documentElement;
        var isDark = html.classList.toggle('dark');
        try { localStorage.setItem('theme', isDark ? 'dark' : 'light'); } catch (e) {}
        // Swap button labels
        btn.querySelector('.dark\\:block')?.classList.toggle('hidden', !isDark);
        btn.querySelector('.dark\\:hidden, .dark\\:block ~ .block')?.classList;
      });
    })();
  </script>
</body>
</html>