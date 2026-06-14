<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        // Daftarkan policy untuk Page tanpa model
        Gate::policy(\App\Filament\Pages\DatabaseBackup::class, \App\Policies\DatabaseBackupPolicy::class);
        Gate::policy(\App\Filament\Pages\ActivityLogs::class, \App\Policies\ActivityLogsPolicy::class);

        // Add custom Request macro for real IP address
        Request::macro('realIp', function () {
            return get_real_ip();
        });

        // Add custom Request macro for location from IP
        Request::macro('ipLocation', function () {
            return get_location_from_ip($this->realIp());
        });
    }
}
