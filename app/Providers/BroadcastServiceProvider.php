<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only load broadcasting routes if the channels file exists
        if (file_exists(base_path('routes/channels.php'))) {
            require base_path('routes/channels.php');
        }
        
        // Alternatively, comment out the broadcasting functionality entirely
        // Broadcast::routes();
        // require base_path('routes/channels.php');
    }
}