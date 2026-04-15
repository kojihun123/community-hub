@props([
  'variant' => 'default',
])

@php
  $baseClass = 'inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold';

  $variantClass = match ($variant) {
      'secondary' => 'bg-stone-100 text-zinc-700',
      'outline' => 'border border-stone-300 bg-white text-zinc-600',
      'danger' => 'bg-rose-50 text-rose-600',
      'success' => 'bg-emerald-50 text-emerald-700',
      'like' => 'bg-rose-50 text-rose-600',
      default => 'bg-rose-50 text-rose-600',
  };
@endphp

<span {{ $attributes->merge(['class' => $baseClass . ' ' . $variantClass]) }}>
  {{ $slot }}
</span>
