@extends('layouts.app')

@section('title', $board->name . ' 게시판')

@section('content')
  <div class="space-y-3">
    <x-ui.section-card
      :title="$board->name"
      action-url="/posts/create"
      action-label="글쓰기"
      action-class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800"
    >
      @if (!empty($board->description))
        <p class="text-sm text-zinc-500">{{ $board->description }}</p>
      @endif
    </x-ui.section-card>

    <section class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
      <div class="border-b border-stone-200 px-4 py-3 md:hidden">
        <h3 class="text-sm font-semibold text-zinc-900">게시글 목록</h3>
      </div>

      <ul class="divide-y divide-stone-100 md:hidden">
        @forelse ($board->posts as $post)
          <li>
            <a
              href="{{ url('/posts/' . $post->id) }}"
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
          @forelse ($board->posts as $post)
            <li>
              <a
                href="{{ url('/posts/' . $post->id) }}"
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
    </section>
  </div>
@endsection
