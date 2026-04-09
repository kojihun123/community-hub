@php
  $sections = [
      [
          'label' => '내정보',
          'href' => route('mypage.profile.edit'),
          'active' => request()->routeIs('mypage.profile.*'),
      ],
      [
          'label' => '알림',
          'href' => route('mypage.notifications.index'),
          'active' => request()->routeIs('mypage.notifications.*'),
      ],
      [
          'label' => '내가 쓴 글',
          'href' => '#',
          'active' => false,
      ],
      [
          'label' => '내가 쓴 댓글',
          'href' => '#',
          'active' => false,
      ],
  ];
@endphp

<aside class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm">
  <div class="space-y-1 border-b border-stone-200 pb-3">
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">My Page</p>
    <h2 class="text-lg font-semibold text-zinc-900">마이페이지</h2>
    <p class="text-sm text-zinc-500">내 정보와 활동을 확인합니다.</p>
  </div>

  <nav class="mt-4 space-y-1" aria-label="마이페이지 메뉴">
    @foreach ($sections as $section)
      <a
        href="{{ $section['href'] }}"
        @class([
          'flex items-center justify-between rounded-xl px-3 py-2 text-sm font-medium transition',
          'bg-zinc-900 text-white shadow-sm' => $section['active'],
          'text-zinc-700 hover:bg-stone-100' => ! $section['active'],
        ])
      >
        <span>{{ $section['label'] }}</span>
        @if ($section['active'])
          <span class="text-xs font-semibold text-zinc-200">현재</span>
        @endif
      </a>
    @endforeach
  </nav>
</aside>
