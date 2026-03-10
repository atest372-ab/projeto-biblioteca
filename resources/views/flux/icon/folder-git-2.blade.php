{{-- Credit: Lucide (https://lucide.dev) --}}

@props([
    'variant' => 'outline',
])

@use('Flux\Flux')

@php
    if ($variant === 'solid') {
        throw new \Exception('The "solid" variant is not supported in Lucide.');
    }

    $variantClass = '';
    if ($variant === 'outline' || $variant === 'solid') {
        $variantClass = '[:where(&)]:size-6';
    } elseif ($variant === 'mini') {
        $variantClass = '[:where(&)]:size-5';
    } elseif ($variant === 'micro') {
        $variantClass = '[:where(&)]:size-4';
    }

    $classes = Flux::classes('shrink-0')->add($variantClass);

    $strokeWidth = 2;
    if ($variant === 'mini') {
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
    <path d="M9 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v5" />
    <circle cx="13" cy="12" r="2" />
    <path d="M18 19c-2.8 0-5-2.2-5-5v8" />
    <circle cx="20" cy="19" r="2" />
</svg>
