@extends ('layouts.app')

@section ('title', '신고 관리')

@section ('content')
  @php
    $statusTabs = [
        ['value' => 'all', 'label' => '전체'],
        ['value' => 'pending', 'label' => '대기'],
        ['value' => 'resolved', 'label' => '처리 완료'],
        ['value' => 'rejected', 'label' => '반려'],
    ];

    $statusLabel = function (string $status): string {
        return match ($status) {
            'resolved' => '처리 완료',
            'rejected' => '반려',
            default => '대기',
        };
    };

    $statusClass = function (string $status): string {
        return match ($status) {
            'resolved' => 'bg-emerald-50 text-emerald-700',
            'rejected' => 'bg-stone-100 text-zinc-600',
            default => 'bg-amber-50 text-amber-700',
        };
    };
  @endphp

  <div class="grid gap-4 lg:grid-cols-[240px_minmax(0,1fr)]">
    <div class="lg:sticky lg:top-4 lg:self-start">
      @include('partials.admin.sidebar')
    </div>

    <div class="space-y-4">
      <x-ui.section-card title="신고 관리">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
          <div class="space-y-1">
            <p class="text-sm text-zinc-500">
              접수된 게시글 신고를 확인하고 처리 상태를 관리합니다.
            </p>
            <p class="text-xs text-zinc-400">
              현재는 게시글 신고만 운영 대상에 포함됩니다.
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-600">
            <x-ui.badge class="bg-amber-50 text-amber-700">
              대기 {{ number_format($pendingCount) }}
            </x-ui.badge>
            <x-ui.badge class="bg-stone-100 text-zinc-600">
              전체 {{ number_format($totalCount) }}
            </x-ui.badge>
          </div>
        </div>
      </x-ui.section-card>

      <section class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
        <div class="border-b border-stone-200 bg-stone-50/70 px-4 py-4">
          <div class="space-y-3">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
              <div class="inline-flex w-full max-w-full items-center gap-1 overflow-x-auto rounded-xl bg-stone-100 p-1">
                @foreach ($statusTabs as $tab)
                  <a
                    href="{{ route('admin.reports.index', array_filter([
                        'status' => $tab['value'] !== 'all' ? $tab['value'] : null,
                        'field' => $field !== 'title' ? $field : null,
                        'q' => filled($keyword) ? $keyword : null,
                    ])) }}"
                    @class([
                      'inline-flex shrink-0 items-center rounded-lg px-3 py-2 text-sm font-medium transition',
                      'bg-white text-zinc-900 shadow-sm' => $statusFilter === $tab['value'],
                      'text-zinc-600 hover:text-zinc-900' => $statusFilter !== $tab['value'],
                    ])
                  >
                    {{ $tab['label'] }}
                  </a>
                @endforeach
              </div>

              <p class="shrink-0 text-xs text-zinc-500">
                최신 접수 순으로 최근 신고를 확인합니다.
              </p>
            </div>

            <form method="get" action="{{ route('admin.reports.index') }}" class="flex flex-col gap-2 lg:flex-row lg:items-center">
              <input type="hidden" name="status" value="{{ $statusFilter }}">

              <select
                name="field"
                class="h-11 rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none transition focus:border-zinc-500 lg:w-36"
              >
                <option value="id" @selected($field === 'id')>ID</option>
                <option value="post_id" @selected($field === 'post_id')>게시글 ID</option>
                <option value="title" @selected($field === 'title')>대상 게시글</option>
                <option value="reporter" @selected($field === 'reporter')>신고자</option>
                <option value="reason" @selected($field === 'reason')>사유</option>
              </select>

              <div class="flex min-w-0 flex-1 items-center gap-2">
                <input
                  type="text"
                  name="q"
                  value="{{ $keyword }}"
                  placeholder="검색어를 입력하세요"
                  class="h-11 min-w-0 flex-1 rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-500"
                />

                <x-ui.button type="submit" class="h-11 whitespace-nowrap">
                  검색
                </x-ui.button>

                @if ($statusFilter !== 'all' || filled($keyword) || $field !== 'title')
                  <x-ui.link-button :href="route('admin.reports.index')" variant="secondary" class="h-11 whitespace-nowrap">
                    초기화
                  </x-ui.link-button>
                @endif
              </div>
            </form>
          </div>
        </div>

        <div class="border-b border-stone-200 px-4 py-3 md:hidden">
          <h3 class="text-sm font-semibold text-zinc-900">신고 목록</h3>
        </div>

        <ul class="divide-y divide-stone-100 md:hidden">
          @forelse ($reports as $report)
            <li x-data="{ actionOpen: false, selectedAction: null }">
              <div class="space-y-3 px-4 py-4">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0 flex-1 space-y-1">
                    <p class="text-xs text-zinc-500">신고 #{{ $report->id }}</p>
                    @if ($report->reportable instanceof \App\Models\Post)
                      <a
                        href="{{ route('posts.show', [$report->reportable->board, $report->reportable]) }}"
                        class="block truncate text-sm font-medium text-zinc-900 hover:text-zinc-700"
                      >
                        #{{ $report->reportable->id }} {{ $report->reportable->title }}
                      </a>
                    @else
                      <p class="truncate text-sm font-medium text-zinc-900">
                        삭제되었거나 확인할 수 없는 게시글
                      </p>
                    @endif
                  </div>

                  <x-ui.badge class="{{ $statusClass($report->status) }}">
                    {{ $statusLabel($report->status) }}
                  </x-ui.badge>
                </div>

                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-zinc-500">
                  <span>신고자 {{ $report->reporter?->name ?? '-' }}</span>
                  <span>{{ $report->created_at->format('Y.m.d H:i') }}</span>
                  @if ($report->handler)
                    <span>처리자 {{ $report->handler->name }}</span>
                  @endif
                </div>

                <p class="rounded-xl bg-stone-50 px-3 py-2 text-sm leading-6 text-zinc-700">
                  {{ $report->reason }}
                </p>

                <div class="flex flex-wrap items-center gap-2">
                  @if ($report->status === 'pending')
                    <x-ui.button
                      type="button"
                      class="h-9 px-3 text-xs"
                      @click="actionOpen = !actionOpen; if (!actionOpen) selectedAction = null"
                    >
                      처리 메뉴
                    </x-ui.button>
                  @elseif (filled($report->handled_note))
                    <p
                      class="rounded-xl bg-stone-100 px-3 py-2 text-xs leading-5 text-zinc-600"
                      title="{{ $report->handled_note }}"
                    >
                      {{ $report->handled_note }}
                    </p>
                  @endif
                </div>

                <form
                  x-show="actionOpen"
                  x-cloak
                  method="post"
                  action="{{ route('admin.reports.update', $report) }}"
                  onsubmit="return confirm('이 신고를 처리하시겠습니까?')"
                  class="space-y-4 rounded-2xl border border-stone-200 bg-white px-4 py-4 shadow-sm"
                >
                  @csrf
                  @method('patch')

                  <input type="hidden" name="decision" x-model="selectedAction">

                  <div class="space-y-2">
                    <div>
                      <p class="text-sm font-semibold text-zinc-900">신고 처리</p>
                      <p class="text-xs text-zinc-500">처리 방향을 먼저 고른 뒤 조치와 메모를 남깁니다.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                      <button
                        type="button"
                        @click="selectedAction = 'resolved'"
                        class="rounded-xl border px-3 py-3 text-left transition"
                        :class="selectedAction === 'resolved'
                          ? 'border-zinc-900 bg-zinc-900 text-white shadow-sm'
                          : 'border-stone-200 bg-stone-50 text-zinc-700 hover:border-stone-300 hover:bg-white'"
                      >
                        <span class="block text-sm font-semibold">처리 완료</span>
                        <span class="mt-1 block text-xs" :class="selectedAction === 'resolved' ? 'text-zinc-200' : 'text-zinc-500'">
                          운영 조치와 제재 여부를 함께 정리합니다.
                        </span>
                      </button>

                      <button
                        type="button"
                        @click="selectedAction = 'rejected'"
                        class="rounded-xl border px-3 py-3 text-left transition"
                        :class="selectedAction === 'rejected'
                          ? 'border-stone-700 bg-stone-700 text-white shadow-sm'
                          : 'border-stone-200 bg-stone-50 text-zinc-700 hover:border-stone-300 hover:bg-white'"
                      >
                        <span class="block text-sm font-semibold">반려</span>
                        <span class="mt-1 block text-xs" :class="selectedAction === 'rejected' ? 'text-stone-200' : 'text-zinc-500'">
                          허위 신고나 처리 대상이 아닌 경우에 사용합니다.
                        </span>
                      </button>
                    </div>
                  </div>

                  <div x-show="selectedAction === 'resolved'" x-cloak class="space-y-3 rounded-xl border border-emerald-100 bg-emerald-50/50 p-3">
                    <p class="text-xs font-medium text-emerald-700">처리 완료 설정</p>
                    <p class="text-xs leading-5 text-emerald-800/80">
                      실제 숨김이나 제재를 하지 않더라도, 검토를 마쳤다면 운영 처리 이력은 남길 수 있습니다.
                    </p>

                    <div class="grid gap-3 sm:grid-cols-2">
                      <label class="space-y-1">
                        <span class="text-xs font-medium text-zinc-600">게시글 처리</span>
                        <select name="content_action" class="h-10 w-full rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none focus:border-zinc-500">
                          <option value="none">검토만 완료 (변경 없음)</option>
                          <option value="hide">게시글 숨김</option>
                          <option value="delete">게시글 삭제</option>
                        </select>
                      </label>

                      <label class="space-y-1">
                        <span class="text-xs font-medium text-zinc-600">사용자 제재</span>
                        <select name="sanction_type" class="h-10 w-full rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none focus:border-zinc-500">
                          <option value="none">제재 없음</option>
                          <option value="warning">경고</option>
                          <option value="suspension_3d">3일 정지</option>
                          <option value="suspension_7d">7일 정지</option>
                          <option value="suspension_30d">30일 정지</option>
                          <option value="ban">영구 정지</option>
                        </select>
                      </label>
                    </div>

                    <label class="space-y-1">
                      <span class="text-xs font-medium text-zinc-600">처리 메모</span>
                      <textarea
                        name="resolved_reason"
                        rows="3"
                        class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none focus:border-zinc-500"
                        placeholder="처리 사유나 메모를 남겨주세요."
                      ></textarea>
                    </label>
                  </div>

                  <div x-show="selectedAction === 'rejected'" x-cloak class="space-y-3 rounded-xl border border-stone-200 bg-stone-50 p-3">
                    <p class="text-xs font-medium text-zinc-700">반려 설정</p>

                    <label class="space-y-1">
                      <span class="text-xs font-medium text-zinc-600">반려 사유</span>
                      <textarea
                        name="rejected_reason"
                        rows="3"
                        class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none focus:border-zinc-500"
                        placeholder="왜 반려했는지 남겨주세요."
                      ></textarea>
                    </label>
                  </div>

                  <div x-show="selectedAction" x-cloak class="flex items-center justify-between gap-3 border-t border-stone-200 pt-3">
                    <p class="text-xs text-zinc-500">실제 처리 저장 로직을 붙이면 바로 운영 화면으로 사용할 수 있습니다.</p>
                    <div class="flex items-center gap-2">
                      <x-ui.button
                        type="button"
                        variant="ghost"
                        class="h-9 px-3 text-xs"
                        @click="selectedAction = null; actionOpen = false"
                      >
                        닫기
                      </x-ui.button>
                        <x-ui.button type="submit" class="h-9 px-3 text-xs" x-bind:disabled="!selectedAction">
                          <span x-text="selectedAction === 'rejected' ? '반려 저장' : '처리 저장'"></span>
                        </x-ui.button>
                      </div>
                  </div>
                </form>
              </div>
            </li>
          @empty
            <li class="px-4 py-10 text-center text-sm text-zinc-500">
              아직 접수된 신고가 없습니다.
            </li>
          @endforelse
        </ul>

        <div class="hidden md:block">
          <div
            class="grid grid-cols-[3.25rem_minmax(0,1.45fr)_7rem_minmax(0,1.15fr)_5.5rem_6.5rem_7rem] gap-4 border-b border-stone-200 bg-stone-50 px-6 py-3 text-xs font-semibold text-zinc-600"
          >
            <span>ID</span>
            <span>게시글</span>
            <span>신고자</span>
            <span>사유</span>
            <span>상태</span>
            <span>접수일</span>
            <span class="text-center">작업</span>
          </div>

          <ul class="divide-y divide-stone-100">
            @forelse ($reports as $report)
              <li x-data="{ actionOpen: false, selectedAction: null }">
                <div class="grid grid-cols-[3.25rem_minmax(0,1.45fr)_7rem_minmax(0,1.15fr)_5.5rem_6.5rem_7rem] items-start gap-4 px-6 py-4 text-sm text-zinc-700 transition hover:bg-stone-50">
                  <span class="text-zinc-500">{{ $report->id }}</span>
                  <span class="min-w-0">
                    @if ($report->reportable instanceof \App\Models\Post)
                      <a
                        href="{{ route('posts.show', [$report->reportable->board, $report->reportable]) }}"
                        class="block overflow-hidden font-medium leading-6 text-zinc-900 hover:text-zinc-700 [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:3]"
                        title="#{{ $report->reportable->id }} {{ $report->reportable->title }}"
                      >
                        #{{ $report->reportable->id }} {{ $report->reportable->title }}
                      </a>
                    @else
                      <span class="block truncate text-zinc-400">
                        삭제되었거나 확인할 수 없는 게시글
                      </span>
                    @endif
                  </span>
                  <span class="truncate">{{ $report->reporter?->name ?? '-' }}</span>
                  <span
                    class="overflow-hidden text-zinc-500 [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:3]"
                    title="{{ $report->reason }}"
                  >
                    {{ $report->reason }}
                  </span>
                  <span>
                    <x-ui.badge class="{{ $statusClass($report->status) }}">
                      {{ $statusLabel($report->status) }}
                    </x-ui.badge>
                  </span>
                  <span class="text-zinc-500">{{ $report->created_at->format('m.d H:i') }}</span>
                  <div class="flex items-center justify-center gap-2">
                    @if ($report->status === 'pending')
                      <x-ui.button
                        type="button"
                        class="h-9 px-3 text-xs whitespace-nowrap"
                        @click="actionOpen = !actionOpen; if (!actionOpen) selectedAction = null"
                      >
                        처리 메뉴
                      </x-ui.button>
                    @elseif (filled($report->handled_note))
                      <p
                        class="overflow-hidden text-center text-xs leading-5 text-zinc-500 [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:3]"
                        title="{{ $report->handled_note }}"
                      >
                        {{ $report->handled_note }}
                      </p>
                    @else
                      <span class="text-xs text-zinc-400">-</span>
                    @endif
                  </div>
                </div>

                <form
                  x-show="actionOpen"
                  x-cloak
                  method="post"
                  action="{{ route('admin.reports.update', $report) }}"
                  onsubmit="return confirm('이 신고를 처리하시겠습니까?')"
                  class="border-t border-stone-200 bg-stone-50/70 px-6 py-5"
                >
                  @csrf
                  @method('patch')

                  <input type="hidden" name="decision" x-model="selectedAction">

                  <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                      <div class="space-y-1">
                        <p class="text-sm font-semibold text-zinc-900">신고 처리</p>
                        <p class="text-xs text-zinc-500">처리 방향을 고른 뒤 필요한 조치와 메모를 남깁니다.</p>
                      </div>

                      <div class="grid w-full max-w-md grid-cols-2 gap-2">
                        <button
                          type="button"
                          @click="selectedAction = 'resolved'"
                          class="rounded-xl border px-3 py-3 text-left transition"
                          :class="selectedAction === 'resolved'
                            ? 'border-zinc-900 bg-zinc-900 text-white shadow-sm'
                            : 'border-stone-200 bg-white text-zinc-700 hover:border-stone-300 hover:bg-stone-50'"
                        >
                          <span class="block text-sm font-semibold">처리 완료</span>
                          <span class="mt-1 block text-xs" :class="selectedAction === 'resolved' ? 'text-zinc-200' : 'text-zinc-500'">
                            운영 조치와 제재 여부를 함께 정리합니다.
                          </span>
                        </button>

                        <button
                          type="button"
                          @click="selectedAction = 'rejected'"
                          class="rounded-xl border px-3 py-3 text-left transition"
                          :class="selectedAction === 'rejected'
                            ? 'border-stone-700 bg-stone-700 text-white shadow-sm'
                            : 'border-stone-200 bg-white text-zinc-700 hover:border-stone-300 hover:bg-stone-50'"
                        >
                          <span class="block text-sm font-semibold">반려</span>
                          <span class="mt-1 block text-xs" :class="selectedAction === 'rejected' ? 'text-stone-200' : 'text-zinc-500'">
                            허위 신고나 처리 대상이 아닌 경우에 사용합니다.
                          </span>
                        </button>
                      </div>
                    </div>

                    <div x-show="selectedAction === 'resolved'" x-cloak class="rounded-2xl border border-emerald-100 bg-white p-4 shadow-sm">
                      <div class="mb-3 space-y-1">
                        <p class="text-xs font-medium text-emerald-700">처리 완료 설정</p>
                        <p class="text-xs leading-5 text-zinc-500">
                          게시글을 그대로 두더라도 검토 완료 자체는 운영 기록으로 남길 수 있습니다.
                        </p>
                      </div>

                      <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(0,1.4fr)]">
                        <label class="space-y-1">
                          <span class="text-xs font-medium text-zinc-600">게시글 처리</span>
                          <select name="content_action" class="h-10 w-full rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none focus:border-zinc-500">
                            <option value="none">검토만 완료 (변경 없음)</option>
                            <option value="hide">게시글 숨김</option>
                            <option value="delete">게시글 삭제</option>
                          </select>
                        </label>

                        <label class="space-y-1">
                          <span class="text-xs font-medium text-zinc-600">사용자 제재</span>
                          <select name="sanction_type" class="h-10 w-full rounded-lg border border-stone-300 bg-white px-3 text-sm text-zinc-900 outline-none focus:border-zinc-500">
                            <option value="none">제재 없음</option>
                            <option value="warning">경고</option>
                            <option value="suspension_3d">3일 정지</option>
                            <option value="suspension_7d">7일 정지</option>
                            <option value="suspension_30d">30일 정지</option>
                            <option value="ban">영구 정지</option>
                          </select>
                        </label>

                        <label class="space-y-1">
                          <span class="text-xs font-medium text-zinc-600">처리 메모</span>
                          <textarea
                            name="resolved_reason"
                            rows="3"
                            class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none focus:border-zinc-500"
                            placeholder="처리 사유나 메모를 남겨주세요."
                          ></textarea>
                        </label>
                      </div>
                    </div>

                    <div x-show="selectedAction === 'rejected'" x-cloak class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm">
                      <label class="space-y-1">
                        <span class="text-xs font-medium text-zinc-600">반려 사유</span>
                        <textarea
                          name="rejected_reason"
                          rows="3"
                          class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none focus:border-zinc-500"
                          placeholder="왜 반려했는지 남겨주세요."
                        ></textarea>
                      </label>
                    </div>

                    <div x-show="selectedAction" x-cloak class="flex items-center justify-between gap-3 border-t border-stone-200 pt-3">
                      <p class="text-xs text-zinc-500">실제 처리 저장 로직을 붙이면 바로 운영 화면으로 사용할 수 있습니다.</p>
                      <div class="flex items-center gap-2">
                        <x-ui.button
                          type="button"
                          variant="ghost"
                          class="h-9 px-3 text-xs"
                          @click="selectedAction = null; actionOpen = false"
                        >
                          닫기
                        </x-ui.button>
                        <x-ui.button type="submit" class="h-9 px-3 text-xs" x-bind:disabled="!selectedAction">
                          <span x-text="selectedAction === 'rejected' ? '반려 저장' : '처리 저장'"></span>
                        </x-ui.button>
                      </div>
                    </div>
                  </div>
                </form>
              </li>
            @empty
              <li class="px-4 py-10 text-center text-sm text-zinc-500">
                아직 접수된 신고가 없습니다.
              </li>
            @endforelse
          </ul>
        </div>

        @if ($reports->hasPages())
          <div class="border-t border-stone-200 px-6 py-4">
            {{ $reports->links() }}
          </div>
        @endif
      
      </section>
    </div>
  </div>
@endsection
