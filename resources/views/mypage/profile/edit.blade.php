@extends('layouts.app')

@section('title', '마이페이지')

@section('content')

  <div class="grid gap-4 lg:grid-cols-[240px_minmax(0,1fr)]">
    <div class="lg:sticky lg:top-4 lg:self-start">
      @include('partials.mypage.sidebar')
    </div>

    <div class="w-full space-y-4 xl:max-w-3xl">
      <x-ui.section-card title="내 정보">     
        <p class="text-sm text-zinc-500">
          내 프로필 정보를 수정합니다.
        </p>
      </x-ui.section-card>

      <x-ui.section-card title="">     
        <form
          method="post"
          action="{{ route('mypage.profile.update') }}"
          class="space-y-8 px-2 sm:px-4"
          enctype="multipart/form-data"
          onsubmit="return confirm('프로필 정보를 저장하시겠습니까?');"
        >
          @csrf
          @method('patch')

          <div class="grid gap-4 sm:grid-cols-[170px_minmax(0,1fr)] sm:items-center">
            <label for="avatar" class="text-sm font-medium text-zinc-700">
              프로필 이미지
            </label>

            <div class="space-y-1">
              <div class="flex flex-col items-start gap-3">
                <div class="h-32 w-32 overflow-hidden rounded-xl border border-stone-200 bg-stone-100">
                  @if ($user->avatar)
                    <img
                      src="{{ Storage::url($user->avatar) }}"
                      alt="프로필 이미지"
                      class="h-full w-full object-cover"
                    >
                  @else
                    <div class="flex h-full w-full items-center justify-center text-xs text-zinc-400">
                      이미지 없음
                    </div>
                  @endif
                </div>

                <input
                  type="file"
                  id="avatar"
                  name="avatar"
                  accept="image/png,image/jpeg,image/webp"
                  class="block w-full max-w-md text-sm text-zinc-600 file:rounded-xl file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white file:transition hover:file:bg-zinc-800"
                >
              </div>

              @error('avatar')
                <p class="text-sm text-rose-600">{{ $message }}</p>
              @enderror
            </div>
          </div>          
      
          <div class="grid gap-4 sm:grid-cols-[170px_minmax(0,1fr)] sm:items-center">
            <label for="name" class="text-sm font-medium text-zinc-700">
              이름
            </label>

            <div class="space-y-1">
              <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                class="h-11 w-full max-w-md rounded-xl border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none transition focus:border-zinc-500 focus:ring-2 focus:ring-zinc-200"
              >

              @error('name')
                <p class="text-sm text-rose-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="grid gap-4 sm:grid-cols-[170px_minmax(0,1fr)] sm:items-start">
            <label for="bio" class="text-sm font-medium text-zinc-700">
              자기소개
            </label>

            <div class="space-y-1">
              <textarea
                id="bio"
                name="bio"
                rows="4"
                class="w-full max-w-md rounded-xl border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-zinc-500 focus:ring-2 focus:ring-zinc-200"
              >{{ old('bio', $user->bio) }}</textarea>

              @error('bio')
                <p class="text-sm text-rose-600">{{ $message }}</p>
              @enderror
            </div>
          </div>          

          <div class="grid gap-4 sm:grid-cols-[170px_minmax(0,1fr)] sm:items-start">
            <label class="text-sm font-medium text-zinc-700">
              이메일
            </label>

            <div class="space-y-1">
              <div class="flex h-11 w-full max-w-md items-center rounded-xl border border-stone-300 bg-stone-100 px-3 text-sm text-zinc-600">
                {{ $user->email }}
              </div>
            </div>
          </div>
          
          <div class="grid gap-4 sm:grid-cols-[170px_minmax(0,1fr)] sm:items-start">
            <label class="text-sm font-medium text-zinc-700">
              권한/상태
            </label>

            <div class="grid gap-3 sm:grid-cols-2">
              <div class="space-y-1">
                <p class="text-xs font-medium text-zinc-500">권한</p>
                <div class="flex h-11 items-center rounded-xl border border-stone-300 bg-stone-100 px-3 text-sm text-zinc-600">
                  {{ $user->roleLabel() }}
                </div>
              </div>

              <div class="space-y-1">
                <p class="text-xs font-medium text-zinc-500">상태</p>
                <div class="flex h-11 items-center rounded-xl border border-stone-300 bg-stone-100 px-3 text-sm text-zinc-600">
                  {{ $user->statusLabel() }}
                </div>
              </div>
            </div>
          </div>

          <div class="grid gap-4 sm:grid-cols-[170px_minmax(0,1fr)] sm:items-start">
            <label class="text-sm font-medium text-zinc-700">
              가입일
            </label>

            <div class="flex h-11 items-center text-sm text-zinc-900">
              {{ $user->created_at?->format('Y-m-d H:i') }}
            </div>
          </div>
       
          <div class="flex justify-end pt-2">
            <x-ui.button type="submit" class="h-11 px-5">변경사항 저장</x-ui.button>
          </div>

        </form>
      </x-ui.section-card>      
    </div>
  </div>

@endsection
