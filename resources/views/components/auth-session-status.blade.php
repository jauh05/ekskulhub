@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-body-md font-medium text-tertiary']) }}>
        {{ $status }}
    </div>
@endif
