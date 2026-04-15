@extends('layouts.app')

@section('title', '내가 쓴 댓글')

@section('content')

  <div class="grid gap-4 lg:grid-cols-[240px_minmax(0,1fr)]">
    <div class="lg:sticky lg:top-4 lg:self-start">
      @include('partials.mypage.sidebar')
    </div>

    <div class="w-full space-y-4 xl:max-w-4xl">
      <x-ui.section-card title="내가 쓴 댓글">
        <p class="text-sm text-zinc-500">
          내가 작성한 댓글을 관리합니다.
        </p>
      </x-ui.section-card>

      <x-ui.section-card title="">
        <div class="border-b border-stone-200 bg-stone-50/70 px-4 py-3">
          <p class="text-sm text-zinc-500">
            작성한 댓글을 최신순으로 확인할 수 있습니다.
          </p>
        </div>

        <div class="border-b border-stone-200 px-4 py-3 md:hidden">
          <h3 class="text-sm font-semibold text-zinc-900">댓글 목록</h3>
        </div>

        <ul class="divide-y divide-stone-100 md:hidden">
          @forelse ($comments as $comment)
            @php
              $canViewPost = $comment->post && $comment->post->status !== 'deleted';
            @endphp

            <li class="px-4 py-4 transition hover:bg-stone-50">
              <div class="space-y-3">
                @if ($canViewPost)
                  <a href="{{ route('posts.show', [$comment->post->board, $comment->post]) }}" class="block">
                    <div class="space-y-3">
                      <div class="flex flex-wrap items-center gap-2">
                        <x-ui.badge>{{ $comment->post->board->name }}</x-ui.badge>
                        <x-ui.badge :variant="$comment->statusBadgeVariant()">{{ $comment->statusLabel() }}</x-ui.badge>
                        @if ($comment->isReply())
                          <x-ui.badge variant="outline">답글</x-ui.badge>
                        @endif
                      </div>

                      <div class="space-y-2">
                        <p class="text-xs text-zinc-500">
                          원문: {{ $comment->post->title }}
                        </p>
                        <p class="text-sm font-medium leading-6 text-zinc-900">
                          {{ $comment->content }}
                        </p>
                        <p class="text-xs text-zinc-500">
                          ID {{ $comment->id }} · {{ $comment->created_at->format('Y.m.d H:i') }}
                        </p>
                      </div>
                    </div>
                  </a>
                @else
                  <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                      <x-ui.badge>{{ $comment->post->board->name }}</x-ui.badge>
                      <x-ui.badge :variant="$comment->statusBadgeVariant()">{{ $comment->statusLabel() }}</x-ui.badge>
                      @if ($comment->isReply())
                        <x-ui.badge variant="outline">답글</x-ui.badge>
                      @endif
                    </div>

                    <div class="space-y-2">
                      <p class="text-xs text-zinc-500">
                        원문: {{ $comment->post->title }}
                      </p>
                      <p class="text-sm font-medium leading-6 text-zinc-500">
                        {{ $comment->content }}
                      </p>
                      <p class="text-xs text-zinc-500">
                        ID {{ $comment->id }} · {{ $comment->created_at->format('Y.m.d H:i') }}
                      </p>
                    </div>
                  </div>
                @endif

                @if ($comment->isVisible())

                <div class="flex items-center justify-end gap-2">
                  <form method="post" action="{{ route('comments.destroy', [$comment->post->board, $comment->post, $comment]) }}" onsubmit="return confirm('이 댓글을 삭제하시겠습니까?')">
                    @csrf
                    @method('delete')

                    <input type="hidden" name="redirect_to" value="{{ route('mypage.comments') }}">

                    <x-ui.button type="submit" variant="danger" class="h-9 px-3 text-xs">
                      삭제
                    </x-ui.button>
                  </form>
                </div>                
                  
                @endif
                
              </div>
            </li>
          @empty
            <li class="px-4 py-10 text-center text-sm text-zinc-500">
              아직 작성한 댓글이 없습니다.
            </li>
          @endforelse
        </ul>

        <div class="hidden md:block">
          <div class="grid grid-cols-[minmax(0,1fr)_20rem] gap-4 border-b border-stone-200 bg-stone-50 px-6 py-3 text-xs font-semibold text-zinc-600">
            <span>댓글</span>
            <span class="text-right">작성일 / 관리</span>
          </div>

          <ul class="divide-y divide-stone-100">
            @forelse ($comments as $comment)
              @php
                $canViewPost = $comment->post && $comment->post->status !== 'deleted';
              @endphp

              <li class="px-6 py-4 transition hover:bg-stone-50">
                <div class="grid grid-cols-[minmax(0,1fr)_20rem] gap-4">
                  @if ($canViewPost)
                    <a href="{{ route('posts.show', [$comment->post->board, $comment->post]) }}" class="min-w-0 space-y-3">
                      <div class="flex flex-wrap items-center gap-2">
                        <x-ui.badge>{{ $comment->post->board->name }}</x-ui.badge>
                        <x-ui.badge :variant="$comment->statusBadgeVariant()">{{ $comment->statusLabel() }}</x-ui.badge>
                        @if ($comment->isReply())
                          <x-ui.badge variant="outline">답글</x-ui.badge>
                        @endif
                        <span class="text-xs text-zinc-400">ID {{ $comment->id }}</span>
                      </div>

                      <div class="space-y-2">
                        <p class="text-sm text-zinc-500">
                          원문: {{ $comment->post->title }}
                        </p>
                        <p class="break-words font-medium leading-6 text-zinc-900">
                          {{ $comment->content }}
                        </p>
                      </div>
                    </a>
                  @else
                    <div class="min-w-0 space-y-3">
                      <div class="flex flex-wrap items-center gap-2">
                        <x-ui.badge>{{ $comment->post->board->name }}</x-ui.badge>
                        <x-ui.badge :variant="$comment->statusBadgeVariant()">{{ $comment->statusLabel() }}</x-ui.badge>
                        @if ($comment->isReply())
                          <x-ui.badge variant="outline">답글</x-ui.badge>
                        @endif
                        <span class="text-xs text-zinc-400">ID {{ $comment->id }}</span>
                      </div>

                      <div class="space-y-2">
                        <p class="text-sm text-zinc-500">
                          원문: {{ $comment->post->title }}
                        </p>
                        <p class="break-words font-medium leading-6 text-zinc-500">
                          {{ $comment->content }}
                        </p>
                      </div>
                    </div>
                  @endif

                  <div class="flex flex-col items-end justify-between gap-3 text-right">
                    <span class="text-sm text-zinc-500">
                      {{ $comment->created_at->format('Y.m.d H:i') }}
                    </span>

                    @if ($comment->isVisible())

                    <div class="flex items-center justify-end gap-2">
                      <form method="post" action="{{ route('comments.destroy', [$comment->post->board, $comment->post, $comment]) }}" onsubmit="return confirm('이 댓글을 삭제하시겠습니까?')">
                        @csrf
                        @method('delete')

                        <input type="hidden" name="redirect_to" value="{{ route('mypage.comments') }}">

                        <x-ui.button type="submit" variant="danger" class="h-9 px-3 text-xs">
                          삭제
                        </x-ui.button>
                      </form>
                    </div>                    
                      
                    @endif

                  </div>
                </div>
              </li>
            @empty
              <li class="px-4 py-10 text-center text-sm text-zinc-500">
                아직 작성한 댓글이 없습니다.
              </li>
            @endforelse
          </ul>
        </div>

        @if ($comments->hasPages())
          <div class="border-t border-stone-200 px-6 py-4">
            {{ $comments->links() }}
          </div>
        @endif
      </x-ui.section-card>
    </div>
  </div>

@endsection
