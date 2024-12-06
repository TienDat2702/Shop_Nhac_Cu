<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('admin.*', function ($view) {
            $order = Order::where('is_new', 1)->get();
            $comment = Comment::where('is_new', 1)->get();
            $view->with([
                'mount_order' => $order,
                'mount_comment' => $comment,
            ]);
        });
    }
}
