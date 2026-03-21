@extends('layouts.app')

@section('title', $board->name . ' 게시판')

@section('content')
  <div class="space-y-3">
    <x-ui.section-card
      :title="$board->name"
      :action-url="auth()->check() ? route('posts.create', $board) : null"
      action-variant="primary"
      :action-label="auth()->check() ? '글쓰기' : null"
      action-class=""
    >
      @if (!empty($board->description))
        <p class="text-sm text-zinc-500">{{ $board->description }}</p>
      @endif
    </x-ui.section-card>

    <section class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
      <div class="border-b border-stone-200 bg-stone-50/70 px-4 py-3">
        <form method="get" action="{{ route('boards.show', $board) }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
          <select
            name="field"
            class="h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none transition focus:border-zinc-500 sm:w-28"
          >
            <option value="id" @selected(($field ?? request('field', 'title')) === 'id')>ID</option>
            <option value="title" @selected(($field ?? request('field', 'title')) === 'title')>제목</option>
            <option value="author" @selected(($field ?? request('field', 'title')) === 'author')>글쓴이</option>
          </select>

          <input
            type="text"
            name="q"
            value="{{ $keyword ?? request('q') }}"
            placeholder="검색어를 입력하세요"
            class="h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-500"
          />

          <div class="flex shrink-0 items-center gap-2">
            <x-ui.button type="submit" class="h-11 whitespace-nowrap">
              검색
            </x-ui.button>

            @if (filled(request('q')))
              <x-ui.link-button :href="route('boards.show', $board)" variant="secondary" class="h-11 whitespace-nowrap">
                초기화
              </x-ui.link-button>
            @endif
          </div>
        </form>
      </div>

      <div class="border-b border-stone-200 px-4 py-3 md:hidden">
        <h3 class="text-sm font-semibold text-zinc-900">게시글 목록</h3>
      </div>

      <ul class="divide-y divide-stone-100 md:hidden">
        @forelse ($posts as $post)
          <li>
            <a
              href="{{ route('posts.show', [$board, $post]) }}"
              class="block px-4 py-4 transition hover:bg-stone-50"
            >
              <div class="flex items-start justify-between gap-3">
                <p class="min-w-0 flex-1 text-sm font-medium text-zinc-900">
                  {{ $post->title }}
                </p>
                <span class="shrink-0 text-xs font-semibold text-rose-500">
                  {{ number_format($post->like_count) }}
                </span>
              </div>

              <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-zinc-500">
                <span>ID {{ $post->id }}</span>
                <span>{{ $post->author_name_snapshot }}</span>
                <span>{{ $post->created_at->format('Y.m.d') }}</span>
                <span>조회 {{ number_format($post->view_count) }}</span>
                <span>추천 {{ number_format($post->like_count) }}</span>
              </div>
            </a>
          </li>
        @empty
          <li class="px-4 py-10 text-center text-sm text-zinc-500">
            아직 등록된 게시글이 없습니다.
          </li>
        @endforelse
      </ul>

      <div class="hidden md:block">
        <div
          class="grid grid-cols-[4rem_minmax(0,1fr)_6rem_6rem_5rem_5rem] gap-3 border-b border-stone-200 bg-stone-50 px-4 py-3 text-xs font-semibold text-zinc-600"
        >
          <span>ID</span>
          <span>제목</span>
          <span>글쓴이</span>
          <span>작성일</span>
          <span class="text-right">조회</span>
          <span class="text-right">추천</span>
        </div>

        <ul class="divide-y divide-stone-100">
          @forelse ($posts as $post)
            <li>
              <a
                href="{{ route('posts.show', [$board, $post]) }}"
                class="grid grid-cols-[4rem_minmax(0,1fr)_6rem_6rem_5rem_5rem] gap-3 px-4 py-3 text-sm text-zinc-700 transition hover:bg-stone-50"
              >
                <span class="text-zinc-500">{{ $post->id }}</span>
                <span class="truncate font-medium text-zinc-900">{{ $post->title }}</span>
                <span class="truncate">{{ $post->author_name_snapshot }}</span>
                <span class="text-zinc-500">{{ $post->created_at->format('m.d') }}</span>
                <span class="text-right text-zinc-500">{{ number_format($post->view_count) }}</span>
                <span class="text-right font-medium text-rose-500">{{ number_format($post->like_count) }}</span>
              </a>
            </li>
          @empty
            <li class="px-4 py-10 text-center text-sm text-zinc-500">
              아직 등록된 게시글이 없습니다.
            </li>
          @endforelse
        </ul>
      </div>

      @if ($posts->hasPages())
        <div class="border-t border-stone-200 px-4 py-4">
          {{ $posts->links() }}
        </div>
      @endif
    
    </section>
  </div>
@endsection
