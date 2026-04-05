{{-- Credit: Lucide (https://lucide.dev) --}}

@use('Flux\Flux')

@props([
    'variant' => 'outline',
])

@php
    if ($variant === 'solid') {
        throw new \Exception('The "solid" variant is not supported in Lucide.');
    }

    if ($variant === 'outline') {
        $variantClass = '[:where(&)]:size-6';
    } elseif ($variant === 'mini') {
        $variantClass = '[:where(&)]:size-5';
    } elseif ($variant === 'micro') {
        $variantClass = '[:where(&)]:size-4';
    }

    $classes = Flux::classes('shrink-0')->add($variantClass);

    if ($variant === 'outline') {
        $strokeWidth = 2;
    } elseif ($variant === 'mini') {
        $strokeWidth = 2.25;
    } elseif ($variant === 'micro') {
        $strokeWidth = 2.5;
    }
@endphp

<svg
    {{ $attributes->class($classes) }}
    data-flux-icon
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $strokeWidth }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
    data-slot="icon"
>
    <path d="m7 15 5 5 5-5" />
    <path d="m7 9 5-5 5 5" />
</svg>
