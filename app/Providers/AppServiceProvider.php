<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
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
    public function boot(): void
    {
    Carbon::setLocale(config('app.locale'));
    View::composer('layouts.navigation', function ($view) {
        if (Auth::check()) {
            $categories = Category::where('user_id', Auth::id())->get();
            $view->with('categories', $categories);
        }
    });
    }
}
