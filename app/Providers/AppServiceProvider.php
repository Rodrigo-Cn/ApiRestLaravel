<?php

namespace App\Providers;

use App\Models\Payment;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{RoomRepositoryInterface,ReserveRepositoryInterface,PaymentRepositoryInterface,GuestRepositoryInterface,DailyRepositoryInterface};

use App\Repositories\Eloquent\{RoomRepository,ReserveRepository,PaymentRepository,GuestRepository,DailyRepository};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
         $this->app->bind(ReserveRepositoryInterface::class, ReserveRepository::class);
         $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
         $this->app->bind(GuestRepositoryInterface::class, GuestRepository::class);
         $this->app->bind(DailyRepositoryInterface::class, DailyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
