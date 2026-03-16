<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\BoardGroup;
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $issueGroup = BoardGroup::where('slug', 'issue')->firstOrFail();
        $gameGroup = BoardGroup::where('slug', 'game')->firstOrFail();
        $sportsGroup = BoardGroup::where('slug', 'sports')->firstOrFail();
        $cultureHobbyGroup = BoardGroup::where('slug', 'culture-hobby')->firstOrFail();
        $lifeInfoGroup = BoardGroup::where('slug', 'life-info')->firstOrFail();

        $boards = [
            [
                'board_group_id' => null,
                'name' => '공지사항',
                'slug' => 'notice',
                'description' => '운영 공지와 안내',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'board_group_id' => $issueGroup->id,
                'name' => '유머',
                'slug' => 'humor',
                'description' => '가볍게 웃고 즐기는 게시판',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'board_group_id' => $issueGroup->id,
                'name' => '핫이슈',
                'slug' => 'hot-issue',
                'description' => '화제가 되는 이슈를 다루는 게시판',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'board_group_id' => $issueGroup->id,
                'name' => '자유토론',
                'slug' => 'free-talk',
                'description' => '주제 제한 없이 자유롭게 이야기하는 게시판',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'board_group_id' => $gameGroup->id,
                'name' => '리그오브레전드',
                'slug' => 'league-of-legends',
                'description' => '리그오브레전드 게시판',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'board_group_id' => $gameGroup->id,
                'name' => '오버워치',
                'slug' => 'overwatch',
                'description' => '오버워치 게시판',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'board_group_id' => $gameGroup->id,
                'name' => '스타크래프트',
                'slug' => 'starcraft',
                'description' => '스타크래프트 게시판',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'board_group_id' => $sportsGroup->id,
                'name' => '축구',
                'slug' => 'football',
                'description' => '축구 게시판',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'board_group_id' => $sportsGroup->id,
                'name' => '야구',
                'slug' => 'baseball',
                'description' => '야구 게시판',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'board_group_id' => $sportsGroup->id,
                'name' => '농구',
                'slug' => 'basketball',
                'description' => '농구 게시판',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'board_group_id' => $cultureHobbyGroup->id,
                'name' => '영화',
                'slug' => 'movie',
                'description' => '영화 게시판',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'board_group_id' => $cultureHobbyGroup->id,
                'name' => '음악',
                'slug' => 'music',
                'description' => '음악 게시판',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'board_group_id' => $cultureHobbyGroup->id,
                'name' => '독서',
                'slug' => 'reading',
                'description' => '독서 게시판',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'board_group_id' => $lifeInfoGroup->id,
                'name' => 'IT기기',
                'slug' => 'it-device',
                'description' => 'IT 기기와 디지털 제품 정보',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'board_group_id' => $lifeInfoGroup->id,
                'name' => '생활팁',
                'slug' => 'life-tip',
                'description' => '생활에 유용한 팁과 정보',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'board_group_id' => $lifeInfoGroup->id,
                'name' => '맛집/여행',
                'slug' => 'food-travel',
                'description' => '맛집과 여행 정보 게시판',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($boards as $board) {
            Board::updateOrCreate(
                ['slug' => $board['slug']],
                [
                    'board_group_id' => $board['board_group_id'],
                    'name' => $board['name'],
                    'description' => $board['description'],
                    'is_active' => $board['is_active'],
                    'sort_order' => $board['sort_order'],
                ]
            );
        }
    }
}
