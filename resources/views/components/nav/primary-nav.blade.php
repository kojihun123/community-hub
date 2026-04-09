<div class="border-b border-stone-200 bg-zinc-900 text-white">
  <div
    class="mx-auto hidden max-w-6xl items-center justify-between px-4 md:flex"
  >
    <nav class="flex items-center">
      <a
        href="{{ url('/') }}"
        class="px-4 py-3 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-white"
      >
        홈
      </a>
      <a
        href="{{ url('/boards') }}"
        class="px-4 py-3 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-white"
      >
        게시판
      </a>
      <a
        href="{{ url('/popular') }}"
        class="px-4 py-3 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-white"
      >
        인기글
      </a>
      <a
        href="{{ url('/boards/notice') }}"
        class="px-4 py-3 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-white"
      >
        공지
      </a>
    </nav>
  </div>

  <div
    x-show="open"
    x-cloak
    x-transition.opacity
    class="border-t border-white/10 bg-zinc-900 md:hidden"
  >
    <div class="space-y-4 px-4 py-4">
      <form
        action="{{ url('/search') }}"
        method="GET"
        class="flex items-center gap-2"
      >
        <input
          type="text"
          name="q"
          placeholder="통합검색"
          class="h-10 w-full rounded-xl border border-zinc-700 bg-zinc-800 px-3 text-sm text-white outline-none placeholder:text-zinc-400 focus:border-zinc-500"
        />
        <x-ui.button
          type="submit"
          variant="secondary"
          class="h-10 shrink-0 rounded-xl border-0 bg-white px-3 text-zinc-900 hover:bg-stone-100"
        >
          검색
        </x-ui.button>
      </form>

      <nav class="flex flex-col gap-1">
        <a
          href="{{ url('/') }}"
          class="rounded-xl px-3 py-2 text-sm text-white/90 transition hover:bg-white/10"
          >홈</a
        >
        <a
          href="{{ url('/boards') }}"
          class="rounded-xl px-3 py-2 text-sm text-white/90 transition hover:bg-white/10"
          >게시판</a
        >
        <a
          href="{{ url('/popular') }}"
          class="rounded-xl px-3 py-2 text-sm text-white/90 transition hover:bg-white/10"
          >인기글</a
        >
        <a
          href="{{ url('/boards/notice') }}"
          class="rounded-xl px-3 py-2 text-sm text-white/90 transition hover:bg-white/10"
          >공지</a
        >
      </nav>

      <div class="border-t border-white/10 pt-4">
        <div class="flex flex-col gap-2 text-sm">
          @auth
            <a
              href="{{ route('mypage.notifications.index') }}"
              class="rounded-xl px-3 py-2 text-white/90 transition hover:bg-white/10"
              >알림</a
            >
            <a
              href="{{ url('/profile') }}"
              class="rounded-xl px-3 py-2 text-white/90 transition hover:bg-white/10"
              >프로필</a
            >
            @if (in_array(optional(auth()->user())->role, ['admin', 'moderator'], true))
              <a
                href="{{ url('/admin') }}"
                class="rounded-xl px-3 py-2 text-white/90 transition hover:bg-white/10"
                >관리자</a
              >
            @endif
            <form method="POST" action="{{ url('/logout') }}">
              @csrf
              <x-ui.button
                type="submit"
                variant="ghost"
                class="w-full justify-start rounded-xl bg-rose-500/15 px-3 py-2 font-medium text-rose-200 hover:bg-rose-500/25 hover:text-rose-100"
              >
                로그아웃
              </x-ui.button>
            </form>
          @else
            <a
              href="{{ url('/login') }}"
              class="rounded-xl px-3 py-2 text-white/90 transition hover:bg-white/10"
              >로그인</a
            >
            <x-ui.link-button
              :href="url('/register')"
              variant="secondary"
              class="justify-start rounded-xl border-0 bg-white px-3 py-2 text-zinc-900 hover:bg-stone-100"
            >
              회원가입
            </x-ui.link-button>
          @endauth
        </div>
      </div>
    </div>
  </div>
</div>
