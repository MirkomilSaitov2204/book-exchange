<?php

namespace App\Providers;

use App\Interfaces\BookInterface;
use App\Interfaces\RequestInterface;
use App\Services\BookService;
use App\Services\RequestService;
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
        $this->app->bind(BookInterface::class, BookService::class);
        $this->app->bind(RequestInterface::class, RequestService::class);

    }
}
