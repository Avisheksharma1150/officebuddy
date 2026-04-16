<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if admin already exists
        $existingAdmin = User::where('email', 'admin@officebuddy.com')->first();
        
        if (!$existingAdmin) {
            // Create Admin User
            User::create([
                'name' => 'System Administrator',
                'email' => 'admin@officebuddy.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'employee_id' => 'ADM001',
                'joining_date' => now(),
            ]);

            echo "Admin User Created:\n";
            echo "Email: admin@officebuddy.com\n";
            echo "Password: admin123\n";
            echo "Employee ID: ADM001\n\n";
        } else {
            echo "Admin user already exists.\n";
        }

        // Create a sample employee for testing
        $existingEmployee = User::where('email', 'employee@officebuddy.com')->first();
        
        if (!$existingEmployee) {
            User::create([
                'name' => 'John Doe',
                'email' => 'employee@officebuddy.com',
                'password' => Hash::make('employee123'),
                'role' => 'employee',
                'employee_id' => 'EMP000',
                'joining_date' => now(),
            ]);

            echo "Sample Employee Created:\n";
            echo "Email: employee@officebuddy.com\n";
            echo "Password: employee123\n";
            echo "Employee ID: EMP000\n";
        } else {
            echo "Sample employee already exists.\n";
        }
    }
}