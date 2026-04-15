@extends('layouts.app')

@section('title', '내가 쓴 글')

@section('content')

  <div class="grid gap-4 lg:grid-cols-[240px_minmax(0,1fr)]">
    <div class="lg:sticky lg:top-4 lg:self-start">
      @include('partials.mypage.sidebar')
    </div>

    <div class="w-full space-y-4 xl:max-w-4xl">
      <x-ui.section-card title="내가 쓴 글">     
        <p class="text-sm text-zinc-500">
          내가 쓴 글을 관리합니다.
        </p>
      </x-ui.section-card>

      <x-ui.section-card title="">     
        <div class="border-b border-stone-200 bg-stone-50/70 px-4 py-3">
          <p class="text-sm text-zinc-500">
            작성한 글을 최신순으로 확인할 수 있습니다.
          </p>
        </div>

        <div class="border-b border-stone-200 px-4 py-3 md:hidden">
          <h3 class="text-sm font-semibold text-zinc-900">게시글 목록</h3>
        </div>

        <ul class="divide-y divide-stone-100 md:hidden">
          @forelse ($posts as $post)
            <li class="px-4 py-4 transition hover:bg-stone-50">
              <div class="space-y-3">
                @if ($post->status === 'deleted')
                  <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                      <x-ui.badge>{{ $post->board->name }}</x-ui.badge>
                      <x-ui.badge :variant="$post->statusBadgeVariant()">{{ $post->statusLabel() }}</x-ui.badge>
                    </div>

                    <div class="space-y-1">
                      <p class="text-sm font-medium text-zinc-500">
                        {{ $post->title }}
                      </p>
                      <p class="text-xs text-zinc-500">
                        ID {{ $post->id }} · {{ $post->created_at->format('Y.m.d H:i') }}
                      </p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 rounded-xl bg-stone-50 px-3 py-2 text-center text-xs text-zinc-600">
                      <div>
                        <p class="font-medium text-zinc-900">{{ number_format($post->comment_count) }}</p>
                        <p>댓글</p>
                      </div>
                      <div>
                        <p class="font-medium text-zinc-900">{{ number_format($post->view_count) }}</p>
                        <p>조회</p>
                      </div>
                      <div>
                        <p class="font-medium text-zinc-900">{{ number_format($post->like_count) }}</p>
                        <p>추천</p>
                      </div>
                    </div>
                  </div>
                @else
                  <a
                    href="{{ route('posts.show', [$post->board, $post]) }}"
                    class="block"
                  >
                    <div class="space-y-3">
                      <div class="flex flex-wrap items-center gap-2">
                        <x-ui.badge>{{ $post->board->name }}</x-ui.badge>
                        <x-ui.badge :variant="$post->statusBadgeVariant()">{{ $post->statusLabel() }}</x-ui.badge>
                      </div>

                      <div class="space-y-1">
                        <p class="text-sm font-medium text-zinc-900">
                          {{ $post->title }}
                        </p>
                        <p class="text-xs text-zinc-500">
                          ID {{ $post->id }} · {{ $post->created_at->format('Y.m.d H:i') }}
                        </p>
                      </div>

                      <div class="grid grid-cols-3 gap-2 rounded-xl bg-stone-50 px-3 py-2 text-center text-xs text-zinc-600">
                        <div>
                          <p class="font-medium text-zinc-900">{{ number_format($post->comment_count) }}</p>
                          <p>댓글</p>
                        </div>
                        <div>
                          <p class="font-medium text-zinc-900">{{ number_format($post->view_count) }}</p>
                          <p>조회</p>
                        </div>
                        <div>
                          <p class="font-medium text-zinc-900">{{ number_format($post->like_count) }}</p>
                          <p>추천</p>
                        </div>
                      </div>
                    </div>
                  </a>
                @endif

                <div class="flex items-center justify-end gap-2">
                  @if ($post->status === 'published')
                    <x-ui.link-button :href="route('posts.edit', [$post->board, $post])" variant="secondary" class="h-9 px-3 text-xs">
                      수정
                    </x-ui.link-button>

                    <form method="post" action="{{ route('posts.destroy', [$post->board, $post]) }}" onsubmit="return confirm('이 게시글을 삭제하시겠습니까?')">
                      @csrf
                      @method('delete')

                      <input type="hidden" name="redirect_to" value="{{ route('mypage.posts') }}">

                      <x-ui.button type="submit" variant="danger" class="h-9 px-3 text-xs">
                        삭제
                      </x-ui.button>
                    </form>
                  @endif
                </div>
              </div>
            </li>
          @empty
            <li class="px-4 py-10 text-center text-sm text-zinc-500">
              아직 작성한 게시글이 없습니다.
            </li>
          @endforelse
        </ul>

        <div class="hidden md:block">
          <div class="grid grid-cols-[minmax(0,1fr)_22rem] gap-4 border-b border-stone-200 bg-stone-50 px-6 py-3 text-xs font-semibold text-zinc-600">
            <span>게시글</span>
            <span class="text-right">작성일 / 활동 수치</span>
          </div>

          <ul class="divide-y divide-stone-100">
            @forelse ($posts as $post)
              <li class="px-6 py-4 transition hover:bg-stone-50">
                <div class="grid grid-cols-[minmax(0,1fr)_22rem] gap-4">
                  @if ($post->status === 'deleted')
                    <div class="min-w-0 space-y-3">
                      <div class="flex flex-wrap items-center gap-2">
                        <x-ui.badge>{{ $post->board->name }}</x-ui.badge>
                        <x-ui.badge :variant="$post->statusBadgeVariant()">{{ $post->statusLabel() }}</x-ui.badge>
                        <span class="text-xs text-zinc-400">ID {{ $post->id }}</span>
                      </div>

                      <p class="break-words font-medium leading-6 text-zinc-500" title="{{ $post->title }}">
                        {{ $post->title }}
                      </p>
                    </div>
                  @else
                    <a
                      href="{{ route('posts.show', [$post->board, $post]) }}"
                      class="min-w-0 space-y-3"
                    >
                      <div class="flex flex-wrap items-center gap-2">
                        <x-ui.badge>{{ $post->board->name }}</x-ui.badge>
                        <x-ui.badge :variant="$post->statusBadgeVariant()">{{ $post->statusLabel() }}</x-ui.badge>
                        <span class="text-xs text-zinc-400">ID {{ $post->id }}</span>
                      </div>

                      <p class="break-words font-medium leading-6 text-zinc-900" title="{{ $post->title }}">
                        {{ $post->title }}
                      </p>
                    </a>
                  @endif

                  <div class="flex flex-col items-end justify-between gap-3 text-right">
                    <span class="text-sm text-zinc-500">
                      {{ $post->created_at->format('Y.m.d H:i') }}
                    </span>

                    <div class="grid w-full grid-cols-3 gap-2 rounded-xl bg-stone-50 px-3 py-2 text-center text-xs text-zinc-600">
                      <div>
                        <p class="font-medium text-zinc-900">{{ number_format($post->comment_count) }}</p>
                        <p>댓글</p>
                      </div>
                      <div>
                        <p class="font-medium text-zinc-900">{{ number_format($post->view_count) }}</p>
                        <p>조회</p>
                      </div>
                      <div>
                        <p class="font-medium text-rose-500">{{ number_format($post->like_count) }}</p>
                        <p>추천</p>
                      </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                      @if ($post->status === 'published')
                        <x-ui.link-button :href="route('posts.edit', [$post->board, $post])" variant="secondary" class="h-9 px-3 text-xs">
                          수정
                        </x-ui.link-button>

                        <form method="post" action="{{ route('posts.destroy', [$post->board, $post]) }}" onsubmit="return confirm('이 게시글을 삭제하시겠습니까?')">
                          @csrf
                          @method('delete')

                          <input type="hidden" name="redirect_to" value="{{ route('mypage.posts') }}">                          

                          <x-ui.button type="submit" variant="danger" class="h-9 px-3 text-xs">
                            삭제
                          </x-ui.button>
                        </form>
                      @endif
                    </div>
                  </div>
                </div>
              </li>
            @empty
              <li class="px-4 py-10 text-center text-sm text-zinc-500">
                아직 작성한 게시글이 없습니다.
              </li>
            @endforelse
          </ul>
        </div>

        @if ($posts->hasPages())
          <div class="border-t border-stone-200 px-6 py-4">
            {{ $posts->links() }}
          </div>
        @endif
      
      
       
      </x-ui.section-card>      
    </div>
  </div>

@endsection
