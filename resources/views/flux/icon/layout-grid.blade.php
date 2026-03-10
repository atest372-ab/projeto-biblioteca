{{-- Credit: Lucide (https://lucide.dev) --}}

@use('Flux\Flux')

@props([
    'variant' => 'outline',
])

@php
    if ($variant === 'solid') {
        throw new \Exception('The "solid" variant is not supported in Lucide.');
    }

    $sizeClass = '';
    if ($variant === 'outline' || $variant === 'solid') {
        $sizeClass = '[:where(&)]:size-6';
    } elseif ($variant === 'mini') {
        $sizeClass = '[:where(&)]:size-5';
    } elseif ($variant === 'micro') {
        $sizeClass = '[:where(&)]:size-4';
    }

    $classes = Flux::classes('shrink-0')->add($sizeClass);

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
    <rect width="7" height="7" x="3" y="3" rx="1" />
    <rect width="7" height="7" x="14" y="3" rx="1" />
    <rect width="7" height="7" x="14" y="14" rx="1" />
    <rect width="7" height="7" x="3" y="14" rx="1" />
</svg>
