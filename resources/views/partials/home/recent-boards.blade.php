<x-ui.section-card title="최근 방문 게시판">
  <ul class="space-y-2">
    @forelse ($recentBoards as $recentBoard)
      <li>
        <a
          href="{{ route('boards.show', $recentBoard['slug']) }}"
          class="block rounded-xl border border-stone-200 bg-stone-50 px-3 py-3 transition hover:border-stone-300 hover:bg-white"
        >
          <p class="text-sm font-medium text-zinc-900">{{ $recentBoard['name'] }}</p>
          <p class="mt-1 text-xs text-zinc-500">최근 방문한 게시판</p>
        </a>
      </li>
    @empty
      <li class="rounded-xl border border-dashed border-stone-200 bg-stone-50 px-3 py-6 text-center text-sm text-zinc-500">
        아직 최근 방문한 게시판이 없습니다.
      </li>
    @endforelse
  </ul>
</x-ui.section-card>
