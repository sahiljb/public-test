<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

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

        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        $profilePath = Vite::asset('resources/images/user-profile.jpeg');
        view()->share('profilePath', $profilePath);
    }
}
