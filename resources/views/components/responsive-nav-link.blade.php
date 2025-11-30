@props(['active'])

@php
$classes = 'gv-responsive-link' . (($active ?? false) ? ' gv-responsive-link--active' : '');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
