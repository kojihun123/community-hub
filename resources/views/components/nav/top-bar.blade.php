<div class="border-b border-stone-200 bg-stone-50">
    <div class="mx-auto hidden max-w-6xl items-center justify-between px-4 py-2 text-xs text-zinc-600 md:flex">
        <div class="flex items-center gap-4">
            <a href="{{ url('/boards/notice') }}" class="transition hover:text-zinc-900">공지사항</a>
            <a href="{{ url('/popular') }}" class="transition hover:text-zinc-900">인기글</a>
        </div>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ url('/notifications') }}" class="transition hover:text-zinc-900">알림</a>
                <a href="{{ url('/profile') }}" class="transition hover:text-zinc-900">프로필</a>

                @if(in_array(optional(auth()->user())->role, ['admin', 'moderator'], true))
                    <a href="{{ url('/admin') }}" class="font-medium text-zinc-900">관리자</a>
                @endif

                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit" class="font-medium text-rose-600 transition hover:text-rose-700">
                        로그아웃
                    </button>
                </form>
            @else
                <a href="{{ url('/login') }}" class="transition hover:text-zinc-900">로그인</a>
                <a href="{{ url('/register') }}" class="transition hover:text-zinc-900">회원가입</a>
            @endauth
        </div>
    </div>
</div>
