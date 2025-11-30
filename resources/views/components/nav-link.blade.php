@props(['active'])

@php
$classes = 'gv-nav-link' . (($active ?? false) ? ' gv-nav-link--active' : '');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
