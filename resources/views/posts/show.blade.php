@extends('layouts.app')

@section ('title', '게시글 보기')

@section ('content')
  <div class="space-y-3">
    <x-ui.section-card 
      :title="$post->board->name"
      :action-url="auth()->check() ? route('posts.create', $post->board) : null"
      action-variant="primary"
      :action-label="auth()->check() ? '글쓰기' : null"
      action-class="">
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
          <x-ui.link-button
            :href="route('boards.show', $post->board)"
            variant="secondary"
          >
            목록으로
          </x-ui.link-button>

          <div class="flex flex-wrap items-center gap-2">
            @auth

              <form method="post" action="{{ route('posts.likes.store', [$post->board, $post]) }}">
                @csrf
                <x-ui.button type="submit" variant="{{ $post->is_liked_by_user ? 'danger' : 'success' }}">
                  좋아요 {{ number_format($post->like_count) }}
                </x-ui.button>
              </form>            
            
              @can('update', $post)     
                <x-ui.link-button
                  :href="route('posts.edit', [$post->board, $post])"
                  variant="outline"                                    
                >
                  수정하기
                </x-ui.link-button>
              @endcan

              @can('delete', $post)
                <form method="post" action="{{ route('posts.destroy', [$post->board, $post]) }}">            
                  @csrf
                  @method('delete')

                  <x-ui.button
                    type="submit"
                    variant="danger"                               
                  >
                    삭제하기
                  </x-ui.button>
                </form>
              @endcan

            @endauth
          </div>
        </div>
      </div>
    </x-ui.section-card>

    <x-ui.section-card :title="'전체 댓글 ' . number_format($post->comment_count) . '개'">
      <div class="space-y-3">
        <ul class="space-y-2">

          @forelse ($post->comments as $comment)

          <li
            x-data="{ editing: @js((string) old('editing_comment_id') === (string) $comment->id), replying: @js((string) old('replying_comment_id') === (string) $comment->id) }"
            class="rounded-lg border border-stone-200 bg-stone-50 px-3 py-3"
          >
            <div class="space-y-2">
              <div class="flex items-center justify-between gap-3 text-xs text-zinc-500">
                <div class="min-w-0">
                  <span class="font-medium text-zinc-800">{{ $comment->user?->name ?? $comment->author_name_snapshot }}</span>
                  <span class="mx-2 text-stone-300">|</span>
                  <span>{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="flex shrink-0 items-center gap-3">

                  @can('update', $comment)
                    <x-ui.button 
                      type="button" 
                      variant="ghost" 
                      class="px-0 py-0 text-xs hover:bg-transparent hover:text-zinc-800"
                      x-show="!editing"
                      @click="editing = true"
                      >
                      수정
                    </x-ui.button>
                  @endcan

                  @can('delete', $comment)
                    <form 
                      method="post" 
                      action="{{ route('comments.destroy', [$post->board, $post, $comment]) }}" 
                      onsubmit="return confirm('댓글을 삭제하시겠습니까?')"
                    >
                      @csrf
                      @method('delete')
                      
                      <x-ui.button 
                        type="submit" 
                        variant="ghost" 
                        class="px-0 py-0 text-xs hover:bg-transparent hover:text-rose-600"
                        x-show="!editing"                          
                        >
                        삭제
                      </x-ui.button>
                    </form>
                  @endcan

                  @auth
                    <x-ui.button 
                      type="button" 
                      variant="ghost" 
                      class="px-0 py-0 text-xs hover:bg-transparent hover:text-zinc-800"
                      x-show="!editing && !replying"
                      @click="replying = true"
                      >
                      답글
                    </x-ui.button>
                  @endauth
                </div>
              </div>

              <div x-show="!editing">
                <div class="text-sm leading-6 text-zinc-800">
                  {{ $comment->content }}
                </div>
              </div>

              @can('update', $comment)
              <div x-show="editing" x-cloak>
                <form method="post" action="{{ route('comments.update', [$post->board, $post, $comment]) }}" class="space-y-2">
                  @csrf
                  @method('patch')
                  <input type="hidden" name="editing_comment_id" value="{{ $comment->id }}">

                  <textarea
                    name="edit_content"
                    rows="4"
                    class="w-full rounded-lg border border-stone-300 bg-white px-3 py-3 text-sm leading-6 text-zinc-900 outline-none"
                  >{{ (string) old('editing_comment_id') === (string) $comment->id ? old('edit_content') : $comment->content }}</textarea>
                  @if ((string) old('editing_comment_id') === (string) $comment->id)
                    <x-input-error :messages="$errors->get('edit_content')" class="mt-2" />
                  @endif

                  <div class="flex justify-end gap-2">
                    <x-ui.button
                      type="button"
                      variant="ghost"
                      @click="editing = false"
                    >
                      취소
                    </x-ui.button>

                    <x-ui.button type="submit">
                      저장
                    </x-ui.button>
                  </div>
                </form>                
              </div>
              @endcan

              @if (count($comment->children))
                @foreach ($comment->children as $child)

                <div
                  x-data="{ editing: @js((string) old('editing_comment_id') === (string) $child->id) }"
                  class="border-l-2 border-stone-200 pl-4 pt-1"
                >
                  <div class="rounded-lg border border-stone-200 bg-white px-3 py-3">
                    <div class="space-y-2">
                      <div class="flex items-center justify-between gap-3 text-xs text-zinc-500">
                        <div class="min-w-0">
                          <span class="font-medium text-zinc-800">{{ $child->user?->name ?? $child->author_name_snapshot }}</span>
                          <span class="mx-2 text-stone-300">|</span>
                          <span>{{ $child->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="flex shrink-0 items-center gap-3">                          
                          @can('update', $child)
                            <x-ui.button 
                              type="button" 
                              variant="ghost" 
                              class="px-0 py-0 text-xs hover:bg-transparent hover:text-zinc-800"
                              x-show="!editing"
                              @click="editing = true"
                              >
                              수정
                            </x-ui.button>
                          @endcan

                          @can('delete', $child)
                            <form 
                              method="post" 
                              action="{{ route('comments.destroy', [$post->board, $post, $child]) }}" 
                              onsubmit="return confirm('댓글을 삭제하시겠습니까?')"
                              >
                              @csrf
                              @method('delete')
                              <x-ui.button 
                                type="submit" 
                                variant="ghost" 
                                class="px-0 py-0 text-xs hover:bg-transparent hover:text-rose-600"
                                x-show="!editing"
                                >
                                삭제
                              </x-ui.button>
                            </form>
                          @endcan                      
                        </div>
                      </div>

                      <div 
                        class="text-sm leading-6 text-zinc-800"
                        x-show="!editing"
                        >
                        {{ $child->content }}
                      </div>

                      @can('update', $child)
                      <div x-show="editing" x-cloak>
                        <form method="post" action="{{ route('comments.update', [$post->board, $post, $child]) }}" class="space-y-2">
                          @csrf
                          @method('patch')
                          <input type="hidden" name="editing_comment_id" value="{{ $child->id }}">

                          <textarea
                            name="edit_content"
                            rows="4"
                            class="w-full rounded-lg border border-stone-300 bg-white px-3 py-3 text-sm leading-6 text-zinc-900 outline-none"
                          >{{ (string) old('editing_comment_id') === (string) $child->id ? old('edit_content') : $child->content }}</textarea>
                          @if ((string) old('editing_comment_id') === (string) $child->id)
                            <x-input-error :messages="$errors->get('edit_content')" class="mt-2" />
                          @endif

                          <div class="flex justify-end gap-2">
                            <x-ui.button
                              type="button"
                              variant="ghost"
                              @click="editing = false"
                            >
                              취소
                            </x-ui.button>

                            <x-ui.button type="submit">
                              저장
                            </x-ui.button>
                          </div>
                        </form>                
                      </div>
                      @endcan

                    </div>
                  </div>
                </div>     

                @endforeach
              @endif

              <div x-show="replying" x-cloak class="border-l-2 border-stone-200 pl-4 pt-1">
                <div class="rounded-lg border border-stone-200 bg-white px-3 py-3">
                  <form method="post" action="{{ route('comments.store', [$post->board, $post]) }}" class="space-y-2">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <input type="hidden" name="replying_comment_id" value="{{ $comment->id }}">

                    <textarea
                      name="reply_content"
                      rows="4"
                      placeholder="답글을 입력해주세요."
                      class="w-full rounded-lg border border-stone-300 bg-white px-3 py-3 text-sm leading-6 text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-500"
                    >{{ (string) old('replying_comment_id') === (string) $comment->id ? old('reply_content') : '' }}</textarea>
                    @if ((string) old('replying_comment_id') === (string) $comment->id)
                      <x-input-error :messages="$errors->get('reply_content')" class="mt-2" />
                    @endif

                    <div class="flex justify-end gap-2">
                      <x-ui.button type="button" variant="ghost" @click="replying = false">
                        취소
                      </x-ui.button>

                      <x-ui.button type="submit">
                        답글 등록
                      </x-ui.button>
                    </div>
                  </form>
                </div>
              </div>

            </div>
          </li>          
            
          @empty

          <li class="rounded-lg border border-dashed border-stone-200 bg-stone-50 px-3 py-6 text-center text-sm text-zinc-500">
            아직 등록된 댓글이 없습니다.
          </li>
          
          @endforelse

        </ul>

        @auth
          <form method="post" action="{{ route('comments.store', [$post->board, $post]) }}" class="space-y-3 rounded-lg border border-stone-200 bg-white p-3">
            @csrf
            <div class="space-y-2">
              <label for="comment-content" class="block text-sm font-medium text-zinc-800">댓글 작성</label>
              <textarea
                id="comment-content"
                name="comment_content"
                rows="4"
                placeholder="댓글을 입력해주세요."
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-3 text-sm leading-6 text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-500"
                required
              >{{ old('comment_content') }}</textarea>
              <x-input-error :messages="$errors->get('comment_content')" class="mt-2" />
            </div>

            <div class="flex justify-end">
              <x-ui.button type="submit">
                댓글 등록
              </x-ui.button>
            </div>
          </form>
        @else
          <div class="rounded-lg border border-dashed border-stone-200 bg-stone-50 px-3 py-3 text-sm text-zinc-500">
            댓글을 작성하려면 로그인해주세요.
          </div>
        @endauth
      </div>
    </x-ui.section-card>

  </div>
@endsection
