<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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
        Carbon::setLocale('id');

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $dropdownNotifications = Auth::user()->notifications
                    ->sortByDesc('created_at')
                    ->filter(function ($notif) {
                        return !$notif->read_at || $notif->read_at->gt(now()->subDays(5));
                    });

                $view->with('dropdownNotifications', $dropdownNotifications);
            }
        });
    }
}
