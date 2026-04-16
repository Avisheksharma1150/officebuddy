<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupOfficeBuddy extends Command
{
    protected $signature = 'officebuddy:setup';
    protected $description = 'Setup OfficeBuddy with admin, salary structures, and employees';

    public function handle()
    {
        $this->info('Setting up OfficeBuddy...');

        // Run migrations
        $this->callSilent('migrate:fresh');

        // Seed database
        $this->callSilent('db:seed');

        $this->info('OfficeBuddy setup completed!');
        $this->info('Admin Login: admin@officebuddy.com / admin123');
        $this->info('All employees use: password123');
    }
}