@php
  $sections = [
      [
          'label' => '대시보드',
          'href' => url('/admin'),
          'active' => request()->is('admin'),
      ],
      [
          'label' => '신고 관리',
          'href' => url('/admin/reports'),
          'active' => request()->is('admin/reports*'),
      ],
      [
          'label' => '게시글 관리',
          'href' => url('/admin/posts'),
          'active' => request()->is('admin/posts*'),
      ],
      [
          'label' => '댓글 관리',
          'href' => url('/admin/comments'),
          'active' => request()->is('admin/comments*'),
      ],
      [
          'label' => '공지 관리',
          'href' => url('/admin/notices'),
          'active' => request()->is('admin/notices*'),
      ],
      [
          'label' => '회원 관리',
          'href' => url('/admin/users'),
          'active' => request()->is('admin/users*'),
      ],
      [
          'label' => '게시판 관리',
          'href' => url('/admin/boards'),
          'active' => request()->is('admin/boards*') || request()->is('admin/board-groups*'),
      ],
  ];
@endphp

<aside class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm">
  <div class="space-y-1 border-b border-stone-200 pb-3">
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">Admin</p>
    <h2 class="text-lg font-semibold text-zinc-900">운영 메뉴</h2>
  </div>

  <nav class="mt-4 space-y-1" aria-label="관리자 메뉴">
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
