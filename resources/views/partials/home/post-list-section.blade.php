<x-ui.section-card
  :title="$title"
  :action-url="$moreUrl"
  action-label="더보기"
>
  <ul class="divide-y divide-stone-100">
    @forelse ($items as $item)
      <li class="py-3 first:pt-0 last:pb-0">
        <a href="{{ $item['url'] }}" class="grid w-full grid-cols-[3.5rem_minmax(0,1fr)] gap-3">
          <div class="h-14 w-14 overflow-hidden rounded-lg bg-stone-200">
            @if (!empty($item['image_url']))
              <img
                src="{{ $item['image_url'] }}"
                alt=""
                class="h-full w-full object-cover"
              />
            @endif
          </div>

          <div class="min-w-0">
            <div class="flex min-w-0 items-center gap-1">
              <p class="min-w-0 flex-1 truncate text-sm font-medium text-zinc-900">
                {{ $item['title'] }}
              </p>
              @if (!empty($item['badge']))
                <x-ui.badge class="shrink-0">{{ $item['badge'] }}</x-ui.badge>
              @endif
            </div>
            <p class="mt-1 text-xs text-zinc-500">{{ $item['meta'] }}</p>
          </div>
        </a>
      </li>
    @empty
      <li class="py-6 text-center text-sm text-zinc-500">
        {{ $emptyMessage ?? '표시할 게시글이 없습니다.' }}
      </li>
    @endforelse
  </ul>
</x-ui.section-card>
