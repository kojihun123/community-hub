<?php

namespace App\Console\Commands;

use App\Models\PopularPost;
use App\Models\Post;
use Illuminate\Console\Command;

class SelectPopularPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:select-popular';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $popularDays  = config('community.popular.days');
        $minimumScore = config('community.popular.minimum_score');

        $posts = Post::where('status', 'published')
            ->where('created_at', '>=', now()->subDays($popularDays ))
            ->whereDoesntHave('popularEntry')
            ->whereRaw('(like_count * 3) + (comment_count * 2) + (view_count * 0.03) >= ?', [$minimumScore])            
            ->get();

        foreach($posts as $post) {
            PopularPost::create([
                'post_id' => $post->id,
                'selected_at' => now(),
            ]);
        }

        $this->info("{$posts->count()}개의 인기글을 선정했습니다.");

        return self::SUCCESS;
    }
}
