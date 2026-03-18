@extends('layouts.app')

@section ('title', '게시글 보기')

@section ('content')
  <div class="space-y-3">
    <x-ui.section-card 
      :title="$post->board->name"
      :action-url="'/boards/' . $post->board->slug . '/posts/create'"
      action-label="글쓰기"
      action-class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800">
      @if (!empty($post->board->description))
        <p class="text-sm text-zinc-500">{{ $post->board->description }}</p>
      @endif
    </x-ui.section-card>

    <x-ui.section-card
      :title="$post->title"
    >
      <div class="space-y-4">
        <div class="flex flex-col gap-3 border-b border-stone-200 pb-3 md:flex-row md:items-center md:justify-between">
          <div class="min-w-0">
            <p class="text-sm text-zinc-600">
              <span class="font-medium text-zinc-800">{{ $post->user?->name ?? $post->author_name_snapshot }}</span>
              <span class="mx-2 text-stone-300">|</span>
              <span>{{ $post->created_at->format('Y.m.d H:i') }}</span>
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-600">
            <span class="rounded-full bg-stone-100 px-2.5 py-1">조회 {{ number_format($post->view_count) }}</span>
            <span class="rounded-full bg-rose-50 px-2.5 py-1 text-rose-600">추천 {{ number_format($post->like_count) }}</span>
            <span class="rounded-full bg-stone-100 px-2.5 py-1">댓글 {{ number_format($post->comment_count) }}</span>
          </div>
        </div>

        <div class="prose prose-stone max-w-none text-sm leading-7">
          {!! $post->content !!}
        </div>

        <div class="flex flex-col gap-3 border-t border-stone-200 pt-3 sm:flex-row sm:items-center sm:justify-between">
          <x-ui.button
            type="button"
            variant="secondary"
            onclick="window.location.href='{{ url('/boards/' . $post->board->slug) }}'"
          >
            목록으로
          </x-ui.button>

          <div class="flex flex-wrap items-center gap-2">
            <x-ui.button type="button" variant="like">
              좋아요 {{ number_format($post->like_count) }}
            </x-ui.button>

            @auth
              @if (auth()->id() === $post->user_id)
                <x-ui.button
                  type="button"
                  variant="outline"
                  disabled
                  class="cursor-not-allowed opacity-50"
                >
                  수정하기
                </x-ui.button>

                <x-ui.button
                  type="button"
                  variant="danger"
                  disabled
                  class="cursor-not-allowed opacity-50"
                >
                  삭제하기
                </x-ui.button>
              @endif
            @endauth
          </div>
        </div>
      </div>
    </x-ui.section-card>

    <x-ui.section-card :title="'전체 댓글 ' . number_format($post->comment_count) . '개'">
      <ul class="space-y-2">
        <li class="rounded-lg border border-stone-200 bg-stone-50 px-3 py-3">
          <div class="space-y-2">
            <div class="flex items-center justify-between gap-3 text-xs text-zinc-500">
              <div class="min-w-0">
                <span class="font-medium text-zinc-800">작성자이름</span>
                <span class="mx-2 text-stone-300">|</span>
                <span>10분 전</span>
              </div>

              <div class="flex shrink-0 items-center gap-3">
                <button type="button" class="transition hover:text-zinc-800">수정</button>
                <button type="button" class="transition hover:text-rose-600">삭제</button>
                <button type="button" class="transition hover:text-zinc-800">답글</button>
              </div>
            </div>

            <div class="text-sm leading-6 text-zinc-800">
              댓글 내용이 여기에 들어갑니다.
            </div>

            <div class="border-l-2 border-stone-200 pl-4 pt-1">
              <div class="rounded-lg border border-stone-200 bg-white px-3 py-3">
                <div class="space-y-2">
                  <div class="flex items-center justify-between gap-3 text-xs text-zinc-500">
                    <div class="min-w-0">
                      <span class="font-medium text-zinc-800">답글작성자</span>
                      <span class="mx-2 text-stone-300">|</span>
                      <span>5분 전</span>
                    </div>

                    <div class="flex shrink-0 items-center gap-3">
                      <button type="button" class="transition hover:text-zinc-800">수정</button>
                      <button type="button" class="transition hover:text-rose-600">삭제</button>
                    </div>
                  </div>

                  <div class="text-sm leading-6 text-zinc-800">
                    대댓글 내용이 여기에 들어갑니다.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>

        <li class="rounded-lg border border-stone-200 bg-stone-50 px-3 py-3">
          <div class="space-y-2">
            <div class="text-xs text-zinc-500">
              <span class="font-medium text-zinc-800">작성자이름</span>
              <span class="mx-2 text-stone-300">|</span>
              <span>10분 전</span>
            </div>

            <div class="text-sm leading-6 text-zinc-800">
              댓글 내용이 여기에 들어갑니다.
            </div>
          </div>
        </li>
      </ul>
    </x-ui.section-card>

  </div>
@endsection
