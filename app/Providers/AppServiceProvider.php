<?php

namespace App\Providers;

use App\Classes\Notification;
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
        //
        view()->composer('dashboard', function ($view) {
            $data = Notification::PrepareDashboard();
            $stackedData = Notification::PrepareReportDashboard();
            $view->with(['data'=> $data,'stackedData'=>$stackedData]);
        });
    }
}
