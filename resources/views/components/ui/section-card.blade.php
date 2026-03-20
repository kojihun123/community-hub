@props([
  'title',
  'actionUrl' => null,
  'actionLabel' => null,
  'actionVariant' => 'ghost',
  'actionClass' => 'text-sm text-zinc-500 transition hover:text-zinc-900',
  'bodyClass' => null,
])

<section class="rounded-2xl border border-stone-200 bg-white p-3 shadow-sm">
  <div class="mb-2 flex items-center justify-between">
    <h2 class="text-base font-semibold text-zinc-900">{{ $title }}</h2>

    @if (!empty($actionUrl) && !empty($actionLabel))
      <x-ui.link-button
        :href="$actionUrl"
        :variant="$actionVariant"
        class="{{ $actionClass }}"
      >
        {{ $actionLabel }}
      </x-ui.link-button>
    @endif
  </div>

  <div @class([$bodyClass])>
    {{ $slot }}
  </div>
</section>
