@props([
  'title',
  'actionUrl' => null,
  'actionLabel' => null,
  'actionClass' => 'text-sm text-zinc-500 transition hover:text-zinc-900',
  'bodyClass' => null,
])

<section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
  <div class="mb-4 flex items-center justify-between">
    <h2 class="text-base font-semibold text-zinc-900">{{ $title }}</h2>

    @if (!empty($actionUrl) && !empty($actionLabel))
      <a
        href="{{ url($actionUrl) }}"
        class="{{ $actionClass }}"
      >
        {{ $actionLabel }}
      </a>
    @endif
  </div>

  <div @class([$bodyClass])>
    {{ $slot }}
  </div>
</section>
