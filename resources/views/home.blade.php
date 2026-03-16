@extends('layouts.app')

@section('title', '홈')

@section('content')
    @php
        $popularItems = [
            [
                'image' => 'https://placehold.co/56x56',
                'title' => '랭크 게임 연패할 때 멘탈 관리 어떻게 하세요? 랭크 게임 연패할 때 멘탈 관리 어떻게 하세요? 랭크 게임 연패할 때 멘탈 관리 어떻게 하세요? 랭크 게임 연패할 때 멘탈 관리 어떻게 하세요? 랭크 게임 연패할 때 멘탈 관리 어떻게 하세요?',
                'likes' => 24,
                'meta' => '자유게시판 · 10분 전',
            ],
            [
                'image' => 'https://placehold.co/56x56',
                'title' => '화장실에 휴지가 없어요 도와주세요',
                'likes' => 5,
                'meta' => '자유게시판 · 20분 전',
            ],
            [
                'image' => 'https://placehold.co/56x56',
                'title' => '오늘 패치 이후 체감되는 점 있으신가요?',
                'likes' => 17,
                'meta' => '리그오브레전드 · 32분 전',
            ],
        ];

        $latestItems = [
            [
                'image' => 'https://placehold.co/56x56',
                'title' => '오늘 새벽에 경기 본 사람?',
                'likes' => 3,
                'meta' => '자유게시판 · 방금 전',
            ],
            [
                'image' => 'https://placehold.co/56x56',
                'title' => '오버워치 복귀하려는데 요즘 분위기 어떤가요',
                'likes' => 1,
                'meta' => '오버워치 · 7분 전',
            ],
            [
                'image' => 'https://placehold.co/56x56',
                'title' => '스타 리마스터 빌드 질문 있습니다',
                'likes' => 6,
                'meta' => '스타크래프트 · 19분 전',
            ],
        ];
    @endphp

    <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
        <div class="space-y-3">
            @include('partials.home.post-list-section', [
                'title' => '인기글',
                'moreUrl' => '/popular',
                'items' => $popularItems,
            ])

            @include('partials.home.post-list-section', [
                'title' => '최신글',
                'moreUrl' => null,
                'items' => $latestItems,
            ])
        </div>

        <div class="space-y-3">
            @include('partials.home.recent-boards')
            @include('partials.home.notices')
        </div>
    </div>
@endsection
