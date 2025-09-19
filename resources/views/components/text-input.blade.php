@props(['disabled' => false])

<input {{ $attributes->merge([
	'class' => 'block w-full rounded-lg border border-gray-300 bg-white p-3 shadow-sm focus:outline-none text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-400',
]) }}>
