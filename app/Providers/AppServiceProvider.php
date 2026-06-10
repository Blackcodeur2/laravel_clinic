<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\PatientRepositoryInterface::class,
            \App\Repositories\PatientRepository::class
        );
        $this->app->bind(
            \App\Repositories\ConsultationRepositoryInterface::class,
            \App\Repositories\ConsultationRepository::class
        );
        $this->app->bind(
            \App\Repositories\FactureRepositoryInterface::class,
            \App\Repositories\FactureRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useTailwind();
    }
}
