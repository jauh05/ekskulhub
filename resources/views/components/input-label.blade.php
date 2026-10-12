@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-label-md text-on-surface-variant']) }}>
    {{ $value ?? $slot }}
</label>
