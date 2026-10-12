<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-error px-4 py-2.5 text-label-lg text-on-error transition-opacity hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-error focus-visible:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
