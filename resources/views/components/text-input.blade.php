@props(['disabled' => false])

<!-- <input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>
 -->
<input {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none text-gray-700']) }}>
