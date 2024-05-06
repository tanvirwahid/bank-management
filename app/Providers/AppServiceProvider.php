<?php

namespace App\Providers;

use App\Contracts\FeeCalculatorFactory as FeeCalculatorFactoryInterface;
use App\Contracts\TransactionHandlerInterface;
use App\FeeCalculators\Factories\FeeCalculatorFactory;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TransactionHandlerInterface::class, TransactionService::class);
        $this->app->bind(FeeCalculatorFactoryInterface::class, FeeCalculatorFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
