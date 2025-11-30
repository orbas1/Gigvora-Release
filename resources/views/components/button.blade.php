<button {{ $attributes->merge(['type' => 'submit', 'class' => 'gv-btn gv-btn-primary disabled:opacity-40']) }}>
    {{ $slot }}
</button>
