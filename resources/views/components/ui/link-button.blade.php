@props([
  'href' => '#',
  'variant' => 'primary',
])

@php
  $baseClass = 'inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition';

  $variantClass = match ($variant) {
      'secondary' => 'border border-stone-200 bg-white text-zinc-700 hover:bg-stone-50',
      'danger' => 'bg-rose-500 text-white hover:bg-rose-600',
      'like' => 'border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100',
      'outline' => 'border border-zinc-300 bg-transparent text-zinc-700 hover:bg-zinc-50',
      'ghost' => 'bg-transparent text-zinc-700 hover:bg-zinc-100',
      'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
      default => 'bg-zinc-900 text-white hover:bg-zinc-800',
  };
@endphp

<a
  href="{{ $href }}"
  {{ $attributes->merge([
      'class' => $baseClass . ' ' . $variantClass,
  ]) }}
>
  {{ $slot }}
</a>
