<section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-base font-semibold text-zinc-900">{{ $title }}</h2>

        @if (!empty($moreUrl))
            <a href="{{ url($moreUrl) }}" class="text-sm text-zinc-500 transition hover:text-zinc-900">더보기</a>
        @endif
    </div>

    <ul class="divide-y divide-stone-100">
        @foreach ($items as $item)
            <li class="py-3 first:pt-0 last:pb-0">
                <a href="#" class="grid w-full grid-cols-[3.5rem_minmax(0,1fr)] gap-3">
                    <div class="h-14 w-14 overflow-hidden rounded-lg bg-stone-200">
                        <img src="{{ $item['image'] }}" alt="" class="h-full w-full object-cover">
                    </div>

                    <div class="min-w-0">
                        <div class="flex min-w-0 items-center gap-1">
                            <p class="min-w-0 flex-1 truncate text-sm font-medium text-zinc-900">
                                {{ $item['title'] }}
                            </p>
                            <span class="shrink-0 text-xs font-semibold text-rose-500">[{{ $item['likes'] }}]</span>
                        </div>
                        <p class="mt-1 text-xs text-zinc-500">{{ $item['meta'] }}</p>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</section>
