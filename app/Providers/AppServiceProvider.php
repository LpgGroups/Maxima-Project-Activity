<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingNotification;

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
                $notifications = TrainingNotification::with('training')
                    ->where('user_id', Auth::id())
                    ->where(function ($q) {
                        $q->whereNull('viewed_at')
                            ->orWhereHas('training', function ($query) {
                                $query->whereColumn('updated_at', '>', 'training_notifications.viewed_at');
                            });
                    })
                    ->latest()
                    ->take(5)
                    ->get();

                $view->with('dropdownNotifications', $notifications);
            }
        });
    }
}
