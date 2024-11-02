<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
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
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadNotifications = Notification::where('receiver', Auth::id())
                    ->where('is_read', false)
                    ->get();

                $allNotifications = Notification::where('receiver', Auth::id())->get();

                $view->with('unreadNotifications', $unreadNotifications);
                $view->with('allNotifications', $allNotifications);
            }
        });
    }
}
