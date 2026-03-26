<x-ui.section-card
  title="운영 공지"
  action-url="/boards/notice"
  action-label="더보기"
>
  <ul class="divide-y divide-stone-100">

    @forelse ($noticePosts as $noticePost)

    <li class="py-3 first:pt-0 last:pb-0">
        <a href="{{ route('posts.show', [$noticePost->board, $noticePost]) }}" class="block">
          <div class="flex items-start gap-2">
          <x-ui.badge class="mt-0.5 shrink-0">공지</x-ui.badge>
          <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-medium text-zinc-900">{{ $noticePost->title }}</p>
            <p class="mt-1 text-xs text-zinc-500">{{ $noticePost->created_at->diffforHumans() }}</p>
          </div>
        </div>
      </a>
    </li>    
      
    @empty
      <li class="py-6 text-center text-sm text-zinc-500">
        아직 등록된 운영 공지가 없습니다.
      </li>
    @endforelse    

  </ul>
</x-ui.section-card>
