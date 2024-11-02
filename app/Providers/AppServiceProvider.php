<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{RoomRepositoryInterface,ReserveRepositoryInterface};

use App\Repositories\Eloquent\{RoomRepository,ReserveRepository};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
         $this->app->bind(ReserveRepositoryInterface::class, ReserveRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
