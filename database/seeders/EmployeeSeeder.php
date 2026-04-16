<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SalaryStructure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // First, ensure we have salary structures
        $this->ensureSalaryStructuresExist();

        $employees = $this->getEmployeesData();

        $created = 0;
        $existing = 0;

        foreach ($employees as $employeeData) {
            // Check if employee already exists
            $existingEmployee = User::where('email', $employeeData['email'])->first();
            
            if (!$existingEmployee) {
                User::create($employeeData);
                $created++;
            } else {
                $existing++;
            }
        }

        echo "Employees processing completed!\n";
        echo "New employees created: {$created}\n";
        echo "Existing employees skipped: {$existing}\n";
        echo "Total employees in database: " . User::where('role', 'employee')->count() . "\n\n";
        
        if ($created > 0) {
            echo "Login Details:\n";
            echo "All employees use password: password123\n\n";
            
            echo "New Employee List:\n";
            foreach ($employees as $index => $employee) {
                if (!User::where('email', $employee['email'])->exists()) {
                    echo ($index + 1) . ". {$employee['name']} - {$employee['email']}\n";
                }
            }
        }
    }

    /**
     * Ensure salary structures exist before creating employees
     */
    private function ensureSalaryStructuresExist()
    {
        $structuresCount = SalaryStructure::count();
        
        if ($structuresCount === 0) {
            $this->command->info('No salary structures found. Creating them now...');
            $this->call(SalaryStructureSeeder::class);
        } else {
            $this->command->info("Found {$structuresCount} salary structures.");
        }
    }

    /**
     * Get employees data with proper salary structure assignments
     */
    private function getEmployeesData()
    {
        // Get salary structures for assignment
        $grade6Structure = SalaryStructure::where('grade_level', '6')->where('employee_type', 'permanent')->first();
        $grade2Structure = SalaryStructure::where('grade_level', '2')->where('employee_type', 'permanent')->first();
        $grade3Structure = SalaryStructure::where('grade_level', '3')->where('employee_type', 'permanent')->first();
        $grade4Structure = SalaryStructure::where('grade_level', '4')->where('employee_type', 'permanent')->first();

        return [
            // 5 Junior Developers (Grade 6)
            [
                'name' => 'Ahmed Rahman',
                'email' => 'ahmed.rahman@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP001',
                'joining_date' => now()->subMonths(12),
                'salary_structure_id' => $grade6Structure ? $grade6Structure->id : null,
            ],
            [
                'name' => 'Fatima Begum',
                'email' => 'fatima.begum@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP002',
                'joining_date' => now()->subMonths(8),
                'salary_structure_id' => $grade6Structure ? $grade6Structure->id : null,
            ],
            [
                'name' => 'Shahriar Hossain',
                'email' => 'shahriar.hossain@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP003',
                'joining_date' => now()->subMonths(6),
                'salary_structure_id' => $grade6Structure ? $grade6Structure->id : null,
            ],
            [
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat.jahan@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP004',
                'joining_date' => now()->subMonths(4),
                'salary_structure_id' => $grade6Structure ? $grade6Structure->id : null,
            ],
            [
                'name' => 'Rahim Islam',
                'email' => 'rahim.islam@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP005',
                'joining_date' => now()->subMonths(2),
                'salary_structure_id' => $grade6Structure ? $grade6Structure->id : null,
            ],

            // 1 Manager (Grade 2)
            [
                'name' => 'Tahmina Akter',
                'email' => 'tahmina.akter@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP006',
                'joining_date' => now()->subYears(3),
                'salary_structure_id' => $grade2Structure ? $grade2Structure->id : null,
            ],

            // 1 Manager (Grade 3)
            [
                'name' => 'Kamal Uddin',
                'email' => 'kamal.uddin@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP007',
                'joining_date' => now()->subYears(2),
                'salary_structure_id' => $grade3Structure ? $grade3Structure->id : null,
            ],

            // 1 Officer (Grade 4)
            [
                'name' => 'Sabrina Chowdhury',
                'email' => 'sabrina.chowdhury@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP008',
                'joining_date' => now()->subYears(1),
                'salary_structure_id' => $grade4Structure ? $grade4Structure->id : null,
            ],

            // 1 Senior Officer (Grade 4)
            [
                'name' => 'Arif Hasan',
                'email' => 'arif.hasan@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP009',
                'joining_date' => now()->subMonths(18),
                'salary_structure_id' => $grade4Structure ? $grade4Structure->id : null,
            ],

            // 1 HR Officer (Grade 4)
            [
                'name' => 'Jannatul Ferdous',
                'email' => 'jannatul.ferdous@officebuddy.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_id' => 'EMP010',
                'joining_date' => now()->subMonths(15),
                'salary_structure_id' => $grade4Structure ? $grade4Structure->id : null,
            ],
        ];
    }
}