<?php

namespace Database\Seeders;

use App\Models\BoardGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $board_groups = [
            [
                'name' => '유머/이슈',
                'slug' => 'issue',
                'description' => '유머, 화제, 시사성 이슈를 다루는 게시판 묶음',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '게임',
                'slug' => 'game',
                'description' => '게임 관련 게시판 묶음',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => '스포츠',
                'slug' => 'sports',
                'description' => '스포츠 관련 게시판 묶음',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => '문화/취미',
                'slug' => 'culture-hobby',
                'description' => '영화, 음악, 취미 생활 관련 게시판 묶음',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => '생활/정보',
                'slug' => 'life-info',
                'description' => '생활 정보, 팁, 디지털 정보 관련 게시판 묶음',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($board_groups as $board_group) {
            BoardGroup::updateOrCreate(
                ['slug' => $board_group['slug']],
                [
                    'name' => $board_group['name'],
                    'description' => $board_group['description'],
                    'is_active' => $board_group['is_active'],
                    'sort_order' => $board_group['sort_order'],
                ]
            );
        }
    }
}
