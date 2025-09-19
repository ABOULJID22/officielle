@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#4f6ba3] dark:text-white']) }}>
    {{ $value ?? $slot }}
</label>
