<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(\App\Repositories\PatientRepository::class, \App\Repositories\PatientRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\MedicalRecordRepository::class, \App\Repositories\MedicalRecordRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\MedicationRepository::class, \App\Repositories\MedicationRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\InvoiceRepository::class, \App\Repositories\InvoiceRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\InvoiceItemRepository::class, \App\Repositories\InvoiceItemRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PrescriptionRepository::class, \App\Repositories\PrescriptionRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SalesStatisticsRepository::class, \App\Repositories\SalesStatisticsRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SalesDetailsRepository::class, \App\Repositories\SalesDetailsRepositoryEloquent::class);
        //:end-bindings:
    }
}
