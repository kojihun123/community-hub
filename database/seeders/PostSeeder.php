<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Board;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PopularPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();

        if ($users->isEmpty()) {
            return;
        }

        $boards = Board::query()
            ->where('slug', '!=', 'notice')
            ->where('is_active', true)
            ->get()
            ->keyBy('slug');

        $imagePaths = [
            'posts/attachments/seed-popular-01.svg',
            'posts/attachments/seed-popular-02.svg',
            'posts/attachments/seed-popular-03.svg',
            'posts/attachments/seed-popular-04.svg',
            'posts/attachments/seed-popular-05.svg',
            'posts/attachments/seed-popular-06.svg',
            'posts/attachments/seed-popular-07.svg',
            'posts/attachments/seed-popular-08.svg',
            'posts/attachments/seed-popular-09.svg',
            'posts/attachments/seed-popular-10.svg',
        ];

        $posts = [
            [
                'board_slug' => 'humor',
                'user_index' => 0,
                'title' => '회사 메신저에서 벌어진 자동완성 대참사',
                'content' => '<p>아침 인사 한 줄 보냈다가 팀 채팅이 한동안 멈췄습니다.</p><p>오늘의 교훈: 자동완성은 믿지 말자.</p>',
                'created_at' => now()->subHours(3),
                'view_count' => 180,
                'attachments' => [0, 1],
                'likes' => 4,
                'comments' => [
                    ['user_index' => 1, 'content' => '이건 진짜 평생 놀림감입니다.', 'children' => [
                        ['user_index' => 2, 'content' => '스크린샷 남아 있으면 더 무섭죠.'],
                    ]],
                    ['user_index' => 3, 'content' => '월요일 아침부터 상상만 해도 아찔하네요.'],
                ],
            ],
            [
                'board_slug' => 'free-talk',
                'user_index' => 2,
                'title' => '요즘 출근길에 들을만한 플레이리스트 추천 좀',
                'content' => '<p>잔잔한 곡도 좋고, 텐션 올리는 곡도 좋습니다.</p><p>아침마다 같은 노래만 들어서 새로운 게 필요해요.</p>',
                'created_at' => now()->subHours(10),
                'view_count' => 96,
                'likes' => 3,
                'comments' => [
                    ['user_index' => 4, 'content' => '브런치 재즈 쪽으로 한 번 들어보세요.'],
                    ['user_index' => 5, 'content' => '출근길엔 신나는 시티팝이 제일입니다.'],
                    ['user_index' => 1, 'content' => '비 오는 날이면 로파이도 괜찮더라고요.'],
                ],
            ],
            [
                'board_slug' => 'hot-issue',
                'user_index' => 4,
                'title' => '이번 주 가장 화제였던 온라인 밈 정리',
                'content' => '<p>하루 만에 온 커뮤니티가 같은 짤만 돌려본 느낌이네요.</p><p>유행 속도가 진짜 너무 빠릅니다.</p>',
                'created_at' => now()->subDay(),
                'view_count' => 320,
                'likes' => 6,
                'attachments' => [2],
                'comments' => [
                    ['user_index' => 6, 'content' => '이번 밈은 텍스트까지 같이 외워졌어요.'],
                    ['user_index' => 7, 'content' => '짤보다 댓글 패러디가 더 웃겼습니다.', 'children' => [
                        ['user_index' => 8, 'content' => '맞아요. 댓글 창이 본체였어요.'],
                    ]],
                ],
            ],
            [
                'board_slug' => 'league-of-legends',
                'user_index' => 1,
                'title' => '패치 후 체감상 가장 강해진 챔프 누구 같음?',
                'content' => '<p>라인전도 괜찮고 한타 기여도도 높은 쪽으로 이야기해봅시다.</p>',
                'created_at' => now()->subDays(2),
                'view_count' => 140,
                'likes' => 2,
                'comments' => [
                    ['user_index' => 2, 'content' => '정글 쪽 체감이 제일 크더라고요.'],
                    ['user_index' => 3, 'content' => '라인전 안정성만 보면 미드가 강해졌어요.'],
                ],
            ],
            [
                'board_slug' => 'overwatch',
                'user_index' => 5,
                'title' => '경쟁전 복귀하려는데 지금 메타 어때요?',
                'content' => '<p>몇 시즌 쉬다가 복귀하려고 합니다.</p><p>예전처럼 탱커 중심인지 궁금해요.</p>',
                'created_at' => now()->subDays(3),
                'view_count' => 88,
                'likes' => 1,
                'comments' => [
                    ['user_index' => 0, 'content' => '조합보단 개인 숙련도가 더 크게 느껴집니다.'],
                ],
            ],
            [
                'board_slug' => 'starcraft',
                'user_index' => 6,
                'title' => '오랜만에 캠페인 다시 하는데 연출이 아직도 좋네요',
                'content' => '<p>예전 감성 그대로인데도 몰입감이 살아 있습니다.</p>',
                'created_at' => now()->subDays(5),
                'view_count' => 75,
                'likes' => 2,
                'comments' => [
                    ['user_index' => 7, 'content' => 'OST 때문에 더 기억에 남는 작품이죠.'],
                ],
            ],
            [
                'board_slug' => 'football',
                'user_index' => 7,
                'title' => '주말 경기 보면서 가장 놀랐던 장면',
                'content' => '<p>개인적으로는 후반 막판 역습 장면이 제일 인상적이었습니다.</p>',
                'created_at' => now()->subHours(20),
                'view_count' => 210,
                'likes' => 5,
                'attachments' => [3],
                'comments' => [
                    ['user_index' => 3, 'content' => '그 장면 때문에 경기 전체 분위기가 바뀌었죠.'],
                    ['user_index' => 4, 'content' => '골키퍼 선방도 꽤 컸습니다.'],
                ],
            ],
            [
                'board_slug' => 'baseball',
                'user_index' => 8,
                'title' => '올 시즌 신인왕 후보 지금 기준으론 누구?',
                'content' => '<p>표본은 적지만 분위기 보면 슬슬 윤곽이 보이는 것 같습니다.</p>',
                'created_at' => now()->subDays(4),
                'view_count' => 130,
                'likes' => 4,
                'comments' => [
                    ['user_index' => 5, 'content' => '지금 페이스면 타자 쪽이 한 발 앞서는 듯해요.'],
                    ['user_index' => 6, 'content' => '투수 쪽도 꾸준하면 충분히 가능하다고 봅니다.'],
                    ['user_index' => 1, 'content' => '결국 6월까지는 봐야 할 것 같아요.'],
                ],
            ],
            [
                'board_slug' => 'basketball',
                'user_index' => 9,
                'title' => '플레이오프 가면 제일 무서운 팀 어디일까요',
                'content' => '<p>정규 시즌 성적보다 단기전 경험이 더 중요해 보입니다.</p>',
                'created_at' => now()->subDays(2),
                'view_count' => 165,
                'likes' => 3,
                'comments' => [
                    ['user_index' => 2, 'content' => '벤치 뎁스 좋은 팀이 결국 유리하죠.'],
                ],
            ],
            [
                'board_slug' => 'movie',
                'user_index' => 3,
                'title' => '최근 본 영화 중 엔딩이 가장 좋았던 작품',
                'content' => '<p>여운이 길게 남는 엔딩이 있는 영화 추천받습니다.</p>',
                'created_at' => now()->subHours(14),
                'view_count' => 145,
                'likes' => 4,
                'attachments' => [4],
                'comments' => [
                    ['user_index' => 7, 'content' => '결말 때문에 다시 보게 되는 영화가 있죠.'],
                    ['user_index' => 8, 'content' => '잔잔한데 끝나고 오래 남는 작품 추천드릴게요.'],
                ],
            ],
            [
                'board_slug' => 'music',
                'user_index' => 4,
                'title' => '작업할 때 듣기 좋은 앨범 하나씩 추천해줘요',
                'content' => '<p>집중 잘 되는 쪽이면 장르 안 가립니다.</p>',
                'created_at' => now()->subDays(6),
                'view_count' => 92,
                'likes' => 2,
                'comments' => [
                    ['user_index' => 0, 'content' => '피아노 앰비언트 계열 추천합니다.'],
                ],
            ],
            [
                'board_slug' => 'reading',
                'user_index' => 5,
                'title' => '짧은 호흡으로 읽기 좋은 에세이 추천 부탁',
                'content' => '<p>퇴근하고 조금씩 읽을 수 있는 책을 찾고 있어요.</p>',
                'created_at' => now()->subDays(3),
                'view_count' => 70,
                'likes' => 1,
                'comments' => [
                    ['user_index' => 9, 'content' => '한 꼭지씩 짧게 끊어 읽기 좋은 책들이 있어요.'],
                ],
            ],
            [
                'board_slug' => 'it-device',
                'user_index' => 6,
                'title' => '무선 이어폰 바꿀까 고민 중인데 배터리 체감 어떤가요',
                'content' => '<p>통화 품질이랑 배터리 유지력이 제일 중요합니다.</p>',
                'created_at' => now()->subHours(8),
                'view_count' => 260,
                'likes' => 5,
                'attachments' => [5, 6],
                'comments' => [
                    ['user_index' => 3, 'content' => '배터리는 확실히 상급기들이 차이가 나더라고요.'],
                    ['user_index' => 5, 'content' => '통화 품질은 마이크 성능도 꼭 보세요.'],
                    ['user_index' => 8, 'content' => '케이스 포함 총 사용 시간이 생각보다 중요합니다.'],
                ],
            ],
            [
                'board_slug' => 'life-tip',
                'user_index' => 2,
                'title' => '자취하면서 제일 잘 샀다고 느낀 소형 가전',
                'content' => '<p>요즘은 작은 가전 하나가 삶의 질을 많이 바꿔주네요.</p>',
                'created_at' => now()->subDays(1),
                'view_count' => 185,
                'likes' => 4,
                'attachments' => [7],
                'comments' => [
                    ['user_index' => 4, 'content' => '전기포트는 진짜 체감이 큽니다.'],
                    ['user_index' => 6, 'content' => '미니 제습기도 생각보다 만족도가 높아요.'],
                ],
            ],
            [
                'board_slug' => 'food-travel',
                'user_index' => 1,
                'title' => '혼자 다녀오기 좋았던 당일치기 여행지 추천',
                'content' => '<p>너무 멀지 않고, 밥집이나 카페도 괜찮은 곳이면 좋겠습니다.</p>',
                'created_at' => now()->subDays(2),
                'view_count' => 220,
                'likes' => 5,
                'attachments' => [8, 9],
                'comments' => [
                    ['user_index' => 7, 'content' => '바다 보이는 소도시 쪽이 혼자 다녀오기 좋아요.'],
                    ['user_index' => 0, 'content' => '기차로 다녀오기 편한 곳도 꽤 괜찮습니다.', 'children' => [
                        ['user_index' => 3, 'content' => '당일치기면 이동 편의성이 제일 중요하죠.'],
                    ]],
                ],
            ],
            [
                'board_slug' => 'humor',
                'user_index' => 8,
                'title' => '커피 쏟고도 침착한 척하다가 더 큰 사고 난 썰',
                'content' => '<p>노트북은 살았는데 자존심은 아직 회복 중입니다.</p><p>괜히 괜찮은 척하면 일이 커지더라고요.</p>',
                'created_at' => now()->subHours(6),
                'view_count' => 260,
                'likes' => 7,
                'attachments' => [0],
                'comments' => [
                    ['user_index' => 2, 'content' => '이건 숨길수록 더 티 나는 종류의 사고네요.'],
                    ['user_index' => 5, 'content' => '키보드 사이에 남은 흔적이 제일 마음 아픕니다.'],
                    ['user_index' => 7, 'content' => '그래도 노트북이 살았으면 절반은 이긴 겁니다.'],
                ],
            ],
            [
                'board_slug' => 'hot-issue',
                'user_index' => 0,
                'title' => '이번 주 온라인에서 가장 빠르게 퍼진 장면 하나',
                'content' => '<p>처음엔 작은 캡처 한 장이었는데 하루 만에 패러디가 쏟아졌습니다.</p>',
                'created_at' => now()->subHours(18),
                'view_count' => 420,
                'likes' => 8,
                'attachments' => [1],
                'comments' => [
                    ['user_index' => 3, 'content' => '원본보다 패러디가 더 많아진 드문 케이스 같아요.'],
                    ['user_index' => 6, 'content' => '짤 하나가 밈이 되기까지 진짜 순식간이네요.'],
                    ['user_index' => 9, 'content' => '커뮤니티마다 제목만 바꿔서 돌더라고요.'],
                ],
            ],
            [
                'board_slug' => 'free-talk',
                'user_index' => 9,
                'title' => '요즘 다들 퇴근하고 뭐 하면서 쉬는지 궁금함',
                'content' => '<p>집에 오면 바로 눕기 바쁜데도 각자 루틴이 있더라고요.</p><p>소소한 힐링 방법 있으면 알려줘요.</p>',
                'created_at' => now()->subHours(30),
                'view_count' => 310,
                'likes' => 6,
                'comments' => [
                    ['user_index' => 1, 'content' => '샤워하고 따뜻한 음료 마시면 하루가 좀 정리됩니다.'],
                    ['user_index' => 4, 'content' => '가벼운 산책이 생각보다 기분 전환이 커요.'],
                    ['user_index' => 8, 'content' => '전 게임 한 판 하고 나면 오히려 머리가 식더라고요.'],
                    ['user_index' => 2, 'content' => '짧게라도 스트레칭하는 날이 컨디션이 낫습니다.'],
                ],
            ],
            [
                'board_slug' => 'league-of-legends',
                'user_index' => 3,
                'title' => '이번 메타에서 솔랭 승률 확실히 챙기는 방법 공유',
                'content' => '<p>무조건 챔프폭을 넓히기보다, 지금 강한 조합을 이해하는 게 먼저인 것 같습니다.</p>',
                'created_at' => now()->subDays(1),
                'view_count' => 380,
                'likes' => 7,
                'comments' => [
                    ['user_index' => 0, 'content' => '챔프보다 동선이 더 중요해졌다는 느낌도 있습니다.'],
                    ['user_index' => 5, 'content' => '상위 티어 리플 보면서 배우는 게 제일 빠르더라고요.'],
                    ['user_index' => 6, 'content' => '라인전에서 욕심 덜 내는 것만으로도 승률이 오릅니다.'],
                ],
            ],
            [
                'board_slug' => 'football',
                'user_index' => 4,
                'title' => '이번 라운드 최고 장면은 이 골이라고 본다',
                'content' => '<p>골 자체도 좋았지만 빌드업 흐름까지 완벽해서 더 인상적이었습니다.</p>',
                'created_at' => now()->subHours(26),
                'view_count' => 500,
                'likes' => 6,
                'attachments' => [3],
                'comments' => [
                    ['user_index' => 2, 'content' => '첫 터치부터 마무리까지 깔끔해서 더 기억에 남아요.'],
                    ['user_index' => 7, 'content' => '그 전개를 끊지 못한 수비도 이해는 됩니다.'],
                    ['user_index' => 9, 'content' => '중계 멘트까지 같이 떠오르는 장면이네요.'],
                ],
            ],
            [
                'board_slug' => 'movie',
                'user_index' => 6,
                'title' => '극장에서 봐야 체감이 사는 영화는 따로 있는 듯',
                'content' => '<p>화면 크기와 사운드가 감상에 미치는 영향이 생각보다 크더라고요.</p>',
                'created_at' => now()->subDays(2),
                'view_count' => 290,
                'likes' => 7,
                'attachments' => [4],
                'comments' => [
                    ['user_index' => 1, 'content' => '집에서는 절대 안 나오는 몰입감이 있죠.'],
                    ['user_index' => 3, 'content' => '특히 음악이 중요한 영화는 극장에서 봐야 합니다.'],
                    ['user_index' => 8, 'content' => '아이맥스든 일반관이든 큰 화면 자체가 다르더라고요.'],
                ],
            ],
            [
                'board_slug' => 'it-device',
                'user_index' => 7,
                'title' => '이번에 써본 보조배터리 중 만족도 제일 높았던 이유',
                'content' => '<p>숫자 스펙보다 실제 충전 속도와 휴대성이 더 중요하다는 걸 새삼 느꼈습니다.</p>',
                'created_at' => now()->subHours(12),
                'view_count' => 460,
                'likes' => 7,
                'attachments' => [5],
                'comments' => [
                    ['user_index' => 0, 'content' => '결국 무게랑 발열이 오래 쓰다 보면 체감이 큽니다.'],
                    ['user_index' => 4, 'content' => '충전기 하나로 다 해결되면 만족도가 확 올라가죠.'],
                    ['user_index' => 6, 'content' => '포트 구성도 은근 중요해서 꼭 체크하게 됩니다.'],
                ],
            ],
            [
                'board_slug' => 'life-tip',
                'user_index' => 5,
                'title' => '집안일 덜 귀찮아지는 작은 습관 하나 추천',
                'content' => '<p>크게 바꾸지 않아도 생활 동선만 정리하면 체감이 꽤 있더라고요.</p>',
                'created_at' => now()->subDays(1),
                'view_count' => 340,
                'likes' => 7,
                'attachments' => [7],
                'comments' => [
                    ['user_index' => 2, 'content' => '바로 제자리에 두는 습관만 들여도 일이 줄어요.'],
                    ['user_index' => 3, 'content' => '청소도 동선에 맞게 나누면 훨씬 덜 힘듭니다.'],
                    ['user_index' => 9, 'content' => '작은 수납함 하나가 생각보다 큰 차이를 만들더라고요.'],
                ],
            ],
            [
                'board_slug' => 'food-travel',
                'user_index' => 2,
                'title' => '주말에 다녀온 소도시 맛집 루트 공유해봄',
                'content' => '<p>동선이 짧고 카페까지 자연스럽게 이어져서 만족도가 높았습니다.</p>',
                'created_at' => now()->subDays(2),
                'view_count' => 410,
                'likes' => 8,
                'attachments' => [8, 9],
                'comments' => [
                    ['user_index' => 1, 'content' => '이런 루트 글이 제일 실용적이라 저장하게 돼요.'],
                    ['user_index' => 4, 'content' => '사진이랑 같이 보니까 동선이 더 잘 들어옵니다.'],
                    ['user_index' => 7, 'content' => '당일치기 코스로도 괜찮아 보여서 좋네요.'],
                ],
            ],
        ];

        DB::transaction(function () use ($boards, $imagePaths, $posts, $users) {
            PopularPost::query()->delete();
            PostLike::query()->delete();
            Comment::query()->delete();
            Attachment::query()->delete();
            Post::query()->delete();

            foreach ($posts as $postData) {
                $board = $boards->get($postData['board_slug']);

                if (! $board) {
                    continue;
                }

                $user = $this->userAt($users, $postData['user_index']);

                $post = Post::create([
                    'board_id' => $board->id,
                    'user_id' => $user?->id,
                    'title' => $postData['title'],
                    'content' => $postData['content'],
                    'author_name_snapshot' => $user?->name ?? '익명',
                    'status' => 'published',
                    'is_notice' => false,
                    'is_pinned' => false,
                    'view_count' => $postData['view_count'],
                    'like_count' => 0,
                    'comment_count' => 0,
                    'created_at' => $postData['created_at'],
                    'updated_at' => $postData['created_at'],
                ]);

                foreach ($postData['attachments'] ?? [] as $sortOrder => $attachmentIndex) {
                    $path = $imagePaths[$attachmentIndex] ?? null;

                    if (! $path) {
                        continue;
                    }

                    Attachment::create([
                        'post_id' => $post->id,
                        'user_id' => $user?->id,
                        'type' => 'image',
                        'original_name' => basename($path),
                        'path' => $path,
                        'mime_type' => $this->mimeType($path),
                        'size' => $this->fileSize($path),
                        'is_temporary' => false,
                        'sort_order' => $sortOrder,
                    ]);

                    $post->content .= '<figure class="image"><img src="' . asset('storage/' . $path) . '" alt=""></figure>';
                    $post->save();
                }

                $this->seedLikes($post, $users, $postData['likes'] ?? 0, $user?->id);
                $this->seedComments($post, $users, $postData['comments'] ?? []);

                $post->update([
                    'like_count' => $post->likes()->count(),
                    'comment_count' => $post->comments()->where('status', 'visible')->count(),
                ]);
            }
        });
    }

    private function seedLikes(Post $post, Collection $users, int $count, ?int $postOwnerId): void
    {
        $likeUsers = $users
            ->when($postOwnerId, fn (Collection $collection) => $collection->where('id', '!=', $postOwnerId))
            ->take($count);

        foreach ($likeUsers as $likeUser) {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $likeUser->id,
            ]);
        }
    }

    private function seedComments(Post $post, Collection $users, array $comments): void
    {
        foreach ($comments as $commentData) {
            $commentUser = $this->userAt($users, $commentData['user_index']);

            $parentComment = Comment::create([
                'post_id' => $post->id,
                'user_id' => $commentUser?->id,
                'parent_id' => null,
                'content' => $commentData['content'],
                'author_name_snapshot' => $commentUser?->name ?? '익명',
                'status' => 'visible',
                'created_at' => $post->created_at->copy()->addMinutes(rand(10, 180)),
                'updated_at' => $post->created_at->copy()->addMinutes(rand(10, 180)),
            ]);

            foreach ($commentData['children'] ?? [] as $childData) {
                $childUser = $this->userAt($users, $childData['user_index']);

                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $childUser?->id,
                    'parent_id' => $parentComment->id,
                    'content' => $childData['content'],
                    'author_name_snapshot' => $childUser?->name ?? '익명',
                    'status' => 'visible',
                    'created_at' => $parentComment->created_at->copy()->addMinutes(rand(3, 60)),
                    'updated_at' => $parentComment->created_at->copy()->addMinutes(rand(3, 60)),
                ]);
            }
        }
    }

    private function userAt(Collection $users, int $index): ?User
    {
        return $users->values()->get($index);
    }

    private function fileSize(string $path): int
    {
        $fullPath = storage_path('app/public/' . $path);

        return is_file($fullPath) ? (int) filesize($fullPath) : 0;
    }

    private function mimeType(string $path): string
    {
        return str_ends_with($path, '.svg') ? 'image/svg+xml' : 'image/png';
    }
}
