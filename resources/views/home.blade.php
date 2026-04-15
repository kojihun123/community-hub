@extends ('layouts.app')

@section ('title', '홈')

@section ('content')
  <div class="grid gap-3 lg:grid-cols-[2fr_1fr]">
    <div class="space-y-3">
      @include ('partials.home.post-list-section', [
                'title' => '인기글',
                'moreUrl' => '/popular',
                'items' => $popularItems,
                'emptyMessage' => '아직 인기글이 없습니다.',
            ])
    </div>

    <div class="space-y-3">
      <x-ui.section-card title="현재 접속">
        <div
          x-data="homePresence({
            total: {{ $onlineCounts['total'] }},
            users: {{ $onlineCounts['users'] }},
            guests: {{ $onlineCounts['guests'] }},
          })"
          class="space-y-3"
        >
          <div class="flex items-end justify-between gap-3">
            <div>
              <p class="text-sm text-zinc-500">홈 화면 기준 실시간 접속 수</p>
              <p class="text-2xl font-semibold text-zinc-900">
                <span x-text="total"></span>명
              </p>
            </div>

            <div class="rounded-2xl bg-stone-100 px-3 py-2 text-right">
              <p class="text-xs font-medium text-zinc-500">회원 / 비회원</p>
              <p class="text-sm font-semibold text-zinc-700">
                <span x-text="users"></span>
                <span class="text-zinc-400">/</span>
                <span x-text="guests"></span>
              </p>
            </div>
          </div>

          <p class="text-xs text-zinc-500">
            사이트를 이용하는 동안 자동으로 갱신됩니다.
          </p>
        </div>
      </x-ui.section-card>

      @include ('partials.home.recent-boards', [
        'recentBoards' => $recentBoards,
      ])
      @include ('partials.home.notices', [
        'noticePosts' => $noticePosts,
      ])
    </div>
  </div>
@endsection
