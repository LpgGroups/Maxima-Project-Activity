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
                $user = Auth::user();

                // Ambil semua notifikasi terbaru (misal 5 hari terakhir atau unread)
                $notifications = $user->notifications
                    ->sortByDesc('created_at')
                    ->filter(function ($notif) {
                        return !$notif->read_at || $notif->created_at->gt(now()->subDays(7));
                    });

                $view->with('dropdownNotifications', $notifications);
            }
        });
    }
}
