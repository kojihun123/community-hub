<div class="border-b border-stone-200 bg-white">
  <div
    class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-5 md:gap-8"
  >
    <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-3">
      <span
        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-zinc-900 text-sm font-bold text-white"
      >
        CH
      </span>
      <span class="truncate text-xl font-semibold tracking-tight text-zinc-900">
        CommunityHub
      </span>
    </a>

    <div class="hidden flex-1 md:block">
      <form
        action="{{ url('/search') }}"
        method="GET"
        class="mx-auto flex max-w-xl items-center gap-2"
      >
        <input
          type="text"
          name="q"
          placeholder="게시판명 & 통합검색"
          class="h-11 w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-500 focus:bg-white"
        />
        <x-ui.button
          type="submit"
          class="h-11 shrink-0 rounded-2xl px-4"
        >
          검색
        </x-ui.button>
      </form>
    </div>

    <x-ui.button
      type="button"
      variant="secondary"
      class="rounded-xl px-3 py-2 md:hidden"
      @click="open = !open"
    >
      메뉴
    </x-ui.button>
  </div>
</div>
