@extends ('layouts.app')

@section ('title', '게시판 목록')

@section ('content')
  <div class="space-y-3">
    <x-ui.section-card title="게시판 목록">
      <p class="text-sm text-zinc-500">관심 있는 주제의 게시판으로 바로 이동해보세요.</p>
    </x-ui.section-card>

    <section class="grid gap-3 lg:grid-cols-3">
      @forelse ($board_groups as $board_group)
        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
          <div class="mb-3 border-b border-stone-100 pb-3">
            <h2 class="text-base font-semibold text-zinc-900">
              {{ $board_group->name }}
            </h2>
            <p class="mt-1 text-sm text-zinc-500">
              {{ $board_group->description }}
            </p>
          </div>

          <ul class="space-y-2">
            @forelse ($board_group->boards as $board)
              <li>
                <a
                  href="{{ url('/boards/' . $board->slug) }}"
                  class="block rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm font-medium text-zinc-900 transition hover:border-stone-300 hover:bg-white"
                >
                  {{ $board->name }}
                </a>
              </li>

            @empty
              <li
                class="rounded-xl border border-dashed border-stone-200 px-4 py-3 text-sm text-zinc-500"
              >
                등록된 게시판이 없습니다.
              </li>
            @endforelse
          </ul>
        </div>

      @empty
        <div
          class="col-span-full rounded-2xl border border-dashed border-stone-200 bg-white p-5 text-sm text-zinc-500 shadow-sm"
        >
          표시할 게시판 그룹이 없습니다.
        </div>
      @endforelse
    </section>
  </div>
@endsection
