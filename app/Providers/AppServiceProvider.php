<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


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
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'admin') {
                $notifications = Auth::user()->unreadNotifications()->take(5)->get();
                $notificationCount = $notifications->count();
            } else {
                $notifications = collect();
                $notificationCount = 0;
            }

            $view->with([
                'dropdownNotifications' => $notifications,
                'notificationCount' => $notificationCount
            ]);
        });
    }
}
