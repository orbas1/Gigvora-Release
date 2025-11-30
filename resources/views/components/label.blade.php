@props(['value'])

<label {{ $attributes->merge(['class' => 'gv-label']) }}>
    {{ $value ?? $slot }}
</label>
