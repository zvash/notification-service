<?php

namespace App\Providers;

use App\Http\Controllers\Api\V1\NotificationController;
use App\Services\NotificationService;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NotificationService::class, function () {
            $request = app(\Illuminate\Http\Request::class);
            $payload = $request->all();
            return new NotificationService($payload);
        });
    }
}
