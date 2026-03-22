@props([
  'variant' => 'default',
])

@php
  $baseClass = 'inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold';

  $variantClass = match ($variant) {
      default => 'bg-rose-50 text-rose-600',
  };
@endphp

<span {{ $attributes->merge(['class' => $baseClass . ' ' . $variantClass]) }}>
  {{ $slot }}
</span>
