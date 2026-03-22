@extends ('layouts.app')

@section ('title', '홈')

@section ('content')
  <div class="grid gap-3 lg:grid-cols-[2fr_1fr]">
    <div class="space-y-3">
      @include ('partials.home.post-list-section', [
                'title' => '인기글',
                'moreUrl' => '/popular',
                'items' => $popularItems,
                'emptyMessage' => '아직 인기글이 없습니다.',
            ])
    </div>

    <div class="space-y-3">
      @include ('partials.home.recent-boards')
      @include ('partials.home.notices')
    </div>
  </div>
@endsection
