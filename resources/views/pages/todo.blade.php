<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Todo — {{ config('app.name', 'Offitrade') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
</head>
<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 mt-8">
    @include('layouts.navbar')

    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-5xl mx-auto px-6 py-16">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Todo</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-8">Formulaire de tâches adapté au design Offitrade.</p>

            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 shadow-sm">
                <form id="todo-form" class="space-y-4">
                    <div>
                        <label for="todo-title" class="block text-sm font-medium mb-1">Titre</label>
                        <input id="todo-title" type="text" required class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2" placeholder="Ex: Appeler la pharmacie X">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="todo-date" class="block text-sm font-medium mb-1">Date limite</label>
                            <input id="todo-date" type="date" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2">
                        </div>
                        <div>
                            <label for="todo-priority" class="block text-sm font-medium mb-1">Priorité</label>
                            <select id="todo-priority" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2">
                                <option value="Basse">Basse</option>
                                <option value="Moyenne" selected>Moyenne</option>
                                <option value="Haute">Haute</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="todo-description" class="block text-sm font-medium mb-1">Description</label>
                        <textarea id="todo-description" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2" placeholder="Détails de la tâche..."></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-[#4f6ba3] hover:bg-[#3f5b93] text-white px-4 py-2 text-sm font-medium">
                            Ajouter la tâche
                        </button>
                    </div>
                    <p id="todo-error" class="text-sm text-red-600 hidden"></p>
                </form>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-3">Liste des tâches</h2>
                <ul id="todo-list" class="space-y-3"></ul>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('todo-form');
            const list = document.getElementById('todo-list');
            const error = document.getElementById('todo-error');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const title = document.getElementById('todo-title').value.trim();
                if (!title) return;

                const date = document.getElementById('todo-date').value;
                const priority = document.getElementById('todo-priority').value;
                const description = document.getElementById('todo-description').value.trim();
                const allowedPriorities = ['Basse', 'Moyenne', 'Haute'];
                error.classList.add('hidden');
                error.textContent = '';

                if (date && !/^\d{4}-\d{2}-\d{2}$/.test(date)) {
                    error.textContent = 'Format de date invalide.';
                    error.classList.remove('hidden');
                    return;
                }

                if (date) {
                    const parsedDate = new Date(date);
                    if (Number.isNaN(parsedDate.getTime()) || parsedDate.toISOString().slice(0, 10) !== date) {
                        error.textContent = 'Date invalide.';
                        error.classList.remove('hidden');
                        return;
                    }
                }

                if (!allowedPriorities.includes(priority)) {
                    error.textContent = 'Priorité invalide.';
                    error.classList.remove('hidden');
                    return;
                }

                const li = document.createElement('li');
                li.className = 'rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4';

                const row = document.createElement('div');
                row.className = 'flex items-start justify-between gap-4';

                const content = document.createElement('div');

                const titleEl = document.createElement('p');
                titleEl.className = 'font-semibold';
                titleEl.textContent = title;

                const descriptionEl = document.createElement('p');
                descriptionEl.className = 'text-sm text-gray-600 dark:text-gray-300 mt-1';
                descriptionEl.textContent = description || 'Aucune description';

                const metaEl = document.createElement('p');
                metaEl.className = 'text-xs text-gray-500 mt-2';
                metaEl.textContent = `Date: ${date || '-'} • Priorité: ${priority}`;

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'text-sm text-red-600 hover:text-red-700';
                removeButton.textContent = 'Supprimer';

                content.appendChild(titleEl);
                content.appendChild(descriptionEl);
                content.appendChild(metaEl);
                row.appendChild(content);
                row.appendChild(removeButton);
                li.appendChild(row);

                removeButton.addEventListener('click', () => li.remove());
                list.prepend(li);
                form.reset();
            });
        });
    </script>
</body>
</html>
