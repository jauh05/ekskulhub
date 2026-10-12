{{-- Ikon Lucide dari resources/svg/lucide. Ukuran diatur lewat class (mis. size-[20px]). --}}
@props(['name'])
@php
    $file = resource_path('svg/lucide/' . basename($name) . '.svg');
    $svg = is_file($file) ? file_get_contents($file) : '';
    $svg = preg_replace('/<!--.*?-->/s', '', $svg);
    $svg = preg_replace('/\sclass="[^"]*"/', '', $svg, 1);
    $svg = str_replace('<svg', '<svg aria-hidden="true" ' . $attributes->merge(['class' => 'lucide lucide-' . basename($name)]), $svg);
@endphp
{!! trim($svg) !!}
