<?php

namespace App\Providers;

use App\Infrastructure\Uam\AggregateRepository\User\UserAggregateRepository;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserAccountMngProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(UserAggregateRepositoryInterface::class, UserAggregateRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
