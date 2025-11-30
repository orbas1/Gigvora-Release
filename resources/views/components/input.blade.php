@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'gv-input' . ($disabled ? ' opacity-60 cursor-not-allowed' : '')]) !!}>
