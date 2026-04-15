@extends('layouts.app')

@section('title', '알림')

@section('content')
  @php
    $tabBaseClass = 'rounded-lg px-3 py-1.5 text-xs font-medium transition';
    $tabActiveClass = 'bg-white text-zinc-700 shadow-sm';
    $tabInactiveClass = 'text-zinc-500 hover:text-zinc-700';
    $emptyMessage = $filter === 'all'
      ? '아직 도착한 알림이 없습니다.'
      : '읽지 않은 알림이 없습니다.';
  @endphp

  <div class="grid gap-4 lg:grid-cols-[240px_minmax(0,1fr)]">
    <div class="lg:sticky lg:top-4 lg:self-start">
      @include('partials.mypage.sidebar')
    </div>

    <div class="w-full space-y-4 xl:max-w-3xl">
      <x-ui.section-card title="알림">
        <p class="text-sm text-zinc-500">
          운영 처리 결과와 커뮤니티 활동 알림을 확인합니다.
        </p>
      </x-ui.section-card>

      <x-ui.section-card title="" body-class="-mx-3 -mb-3">
        <div class="space-y-4 border-b border-stone-200 bg-stone-50/70 px-4 py-3">
          <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div class="space-y-2">
              <p class="text-sm text-zinc-500">
                최근 알림부터 순서대로 표시됩니다.
              </p>

              <div class="inline-flex items-center rounded-xl border border-stone-200 bg-stone-50 p-1">
                <a
                  href="{{ route('mypage.notifications.index', ['filter' => 'all']) }}"
                  class="{{ $tabBaseClass }} {{ $filter === 'all' ? $tabActiveClass : $tabInactiveClass }}"
                >
                  전체
                </a>

                <a
                  href="{{ route('mypage.notifications.index', ['filter' => 'unread']) }}"
                  class="{{ $tabBaseClass }} {{ $filter === 'unread' ? $tabActiveClass : $tabInactiveClass }}"
                >
                  읽지 않음
                </a>
              </div>
            </div>

            <div class="flex items-center gap-2">
              <form method="post" action="{{ route('mypage.notifications.read-all') }}" onsubmit="return confirm('모두 읽음 처리 하시겠습니까?')">
                @csrf
                @method('patch')

                <x-ui.button type="submit" variant="outline" class="h-10 px-3 text-sm">
                  모두 읽음 처리
                </x-ui.button>
              </form>
            </div>
          </div>
        </div>

        @if ($notifications->isNotEmpty())
          <ul class="divide-y divide-stone-100">
            @foreach ($notifications as $notification)
              <li class="px-4 py-4 transition hover:bg-stone-50">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                      @if (! $notification->isRead())
                        <x-ui.badge>읽지 않음</x-ui.badge>
                      @endif
                      <x-ui.badge class="bg-stone-100 text-zinc-700">
                        {{ $notification->typeLabel() }}
                      </x-ui.badge>
                    </div>

                    <div class="space-y-1">
                      @if ($notification->link)
                        <a href="{{ $notification->link }}" class="block rounded-xl transition hover:bg-stone-50">
                          <p class="font-semibold text-zinc-900">
                            {{ $notification->title }}
                          </p>
                          <p class="text-sm leading-6 text-zinc-600">
                            {{ $notification->message }}
                          </p>
                        </a>
                      @else
                        <div>
                          <p class="font-semibold text-zinc-900">
                            {{ $notification->title }}
                          </p>
                          <p class="text-sm leading-6 text-zinc-600">
                            {{ $notification->message }}
                          </p>
                        </div>
                      @endif
                    </div>
                  </div>

                  <span class="shrink-0 text-xs text-zinc-400">
                    {{ $notification->created_at->diffForHumans() }}
                  </span>
                </div>
              </li>
            @endforeach
          </ul>

          @if ($notifications->hasPages())
            <div class="border-t border-stone-200 px-6 py-4">
              {{ $notifications->links() }}
            </div>
          @endif
        @else
          <div class="rounded-2xl border border-dashed border-stone-200 bg-stone-50 px-4 py-10 text-center">
            <p class="text-sm font-medium text-zinc-700">{{ $emptyMessage }}</p>
          </div>
        @endif
      </x-ui.section-card>
    </div>
  </div>
@endsection
