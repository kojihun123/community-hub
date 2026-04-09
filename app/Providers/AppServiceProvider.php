<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\Policies\ReportPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Report::class, ReportPolicy::class);

        View::composer([
            'components.nav.top-bar',
            'components.nav.primary-nav',
        ], function($view){
            $unreadNotificationCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;

            $view->with('unreadNotificationCount', $unreadNotificationCount);
        });
    }
}
