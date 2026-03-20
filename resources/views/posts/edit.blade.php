@extends('layouts.app')

@section ('title', '게시글 수정')

@section ('content')
  <div class="space-y-3">
    <x-ui.section-card
      :title="$post->board->name"
    >
      @if (!empty($post->board->description))
        <p class="text-sm text-zinc-500">{{ $post->board->description }}</p>
      @endif
    </x-ui.section-card>

    <x-ui.section-card title="글 수정">
      <form
        class="space-y-5"
        method="post"
        action="{{ route('posts.update', [$post->board, $post]) }}"
        data-post-editor
      >

        @csrf
        @method('patch')

        <div class="space-y-2">
          <label for="board" class="block text-sm font-medium text-zinc-800">게시판</label>
          <input
            id="board"
            type="text"
            value="{{ $post->board->name }}"
            readonly
            class="h-11 w-full rounded-lg border border-stone-200 bg-stone-100 px-3 text-sm text-zinc-600 outline-none"
          />          
        </div>

        <div class="space-y-2">
          <label for="title" class="block text-sm font-medium text-zinc-800">제목</label>
          <input
            id="title"
            name="title"
            type="text"
            placeholder="제목을 입력하세요 (255자 미만)"
            class="h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-500"
            value="{{ old('title', $post->title) }}"
            required
          />
          <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div class="space-y-2">
          <label for="content" class="block text-sm font-medium text-zinc-800">본문</label>
          <input
            id="content"
            name="content"
            type="hidden"
            value="{{ old('content', $post->content) }}"
          />
          <div class="editor-container editor-container_classic-editor">
            <div class="editor-container__editor">
              <div id="editor"></div>
            </div>
          </div>
          <x-input-error :messages="$errors->get('content')" class="mt-2" />
        </div>

        <div class="flex justify-end border-t border-stone-200 pt-4">
          <div class="flex flex-wrap items-center gap-2">
            <x-ui.link-button
              :href="route('boards.show', $post->board->slug)"
              variant="secondary"
            >
              취소
            </x-ui.link-button>
            <x-ui.button type="submit">
              수정하기
            </x-ui.button>
          </div>
        </div>
      </form>
    </x-ui.section-card>
  </div>
@endsection

@push('styles')
  <style>
    .editor-container_classic-editor .ck-editor__editable_inline {
      min-height: 24rem;
    }
  </style>
@endpush
