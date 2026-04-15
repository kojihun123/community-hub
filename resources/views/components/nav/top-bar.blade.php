<div class="border-b border-stone-200 bg-stone-50">
  <div
    class="mx-auto hidden max-w-6xl items-center justify-between px-4 py-2 text-xs text-zinc-600 md:flex"
  >
    <div class="flex items-center gap-4">
      <a
        href="{{ url('/boards/notice') }}"
        class="transition hover:text-zinc-900"
        >공지사항</a
      >
      <a href="{{ url('/popular') }}" class="transition hover:text-zinc-900"
        >인기글</a
      >
      @if (in_array(optional(auth()->user())->role, ['admin', 'moderator'], true))
        <a href="{{ url('/admin') }}" class="font-medium text-red-600 font-extrabold"
          >신고관리</a
        >
      @endif      
    </div>

    <div class="flex items-center gap-3">
      @auth
        <a
          href="{{ route('mypage.notifications.index') }}"
          class="inline-flex items-center gap-1 transition hover:text-zinc-900"
        >
          알림
          @if ($unreadNotificationCount > 0)
            <x-ui.badge>{{ $unreadNotificationCount }}</x-ui.badge>
          @endif
        </a>

        <div x-data="{open: false}" class="relative">
          <button
            type="button"
            @click="open = !open"
            class="flex items-center"
          >
            <div class="relative flex h-10 w-10 min-h-10 min-w-10 flex-none items-center justify-center overflow-hidden rounded-full bg-stone-100 text-[10px] text-zinc-400 ring-1 ring-stone-200">
              @if (auth()->user()->avatar)
                <img
                  src="{{ Storage::url(auth()->user()->avatar) }}"
                  alt="프로필 이미지"
                  class="absolute inset-0 h-full w-full object-cover"
                >
              @else
                <span>없음</span>
              @endif
            </div>
          </button>

          <div
            x-show="open"
            x-cloak
            x-transition.opacity.scale.origin.top.right
            @click.outside="open = false"
            class="absolute right-0 z-20 mt-2 w-64 rounded-2xl border border-stone-200 bg-white p-3 shadow-lg"
          >
            <div class="flex flex-col items-center gap-3 border-b border-stone-100 pb-3">
              <div class="relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-full bg-stone-100 text-xs text-zinc-400 ring-1 ring-stone-200">
                @if (auth()->user()->avatar)
                  <img
                    src="{{ Storage::url(auth()->user()->avatar) }}"
                    alt="프로필 이미지"
                    class="absolute inset-0 h-full w-full object-cover"
                  >
                @else
                  <span>없음</span>
                @endif
              </div>

              <div class="space-y-1 text-center">
                <p class="text-sm font-semibold text-zinc-900">
                  {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-zinc-500">
                  {{ auth()->user()->email }}
                </p>
              </div>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-2">
              <a
                href="{{ route('mypage.index') }}"
                class="inline-flex h-10 items-center justify-center rounded-xl border border-stone-200 px-3 text-sm text-zinc-700 transition hover:bg-stone-50"
              >
                마이페이지
              </a>

              <form method="POST" action="{{ url('/logout') }}" class="w-full">
                @csrf
                <button
                  type="submit"
                  class="inline-flex h-10 w-full items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-3 text-sm text-rose-600 transition hover:bg-rose-100"
                >
                  로그아웃
                </button>
              </form>
            </div>            
          </div>
        </div>

      @else
        <a href="{{ url('/login') }}" class="transition hover:text-zinc-900"
          >로그인</a
        >
        <a href="{{ url('/register') }}" class="transition hover:text-zinc-900"
          >회원가입</a
        >
      @endauth
    </div>
  </div>
</div>
