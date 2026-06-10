<?php

namespace App\Providers;

use App\Models\ClinicSetting;
use App\Repositories\ConsultationRepository;
use App\Repositories\ConsultationRepositoryInterface;
use App\Repositories\FactureRepository;
use App\Repositories\FactureRepositoryInterface;
use App\Repositories\PatientRepository;
use App\Repositories\PatientRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PatientRepositoryInterface::class,
            PatientRepository::class
        );
        $this->app->bind(
            ConsultationRepositoryInterface::class,
            ConsultationRepository::class
        );
        $this->app->bind(
            FactureRepositoryInterface::class,
            FactureRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Share clinic settings with every view
        View::composer('*', function ($view) {
            static $settings = null;
            $settings ??= ClinicSetting::getInstance();
            $view->with('clinicSettings', $settings);
        });
    }
}
