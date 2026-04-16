<?php

namespace Database\Seeders;

use App\Models\SalaryStructure;
use Illuminate\Database\Seeder;

class SalaryStructureSeeder extends Seeder
{
    public function run()
    {
        $gradeLevels = array_keys(SalaryStructure::GRADE_LEVELS);
        $employeeTypes = array_keys(SalaryStructure::EMPLOYEE_TYPES);

        $created = 0;
        $existing = 0;
        $structures = [];

        foreach ($gradeLevels as $grade) {
            foreach ($employeeTypes as $type) {
                // Check if structure already exists
                $existingStructure = SalaryStructure::where('grade_level', $grade)
                    ->where('employee_type', $type)
                    ->first();

                if (!$existingStructure) {
                    $structureData = SalaryStructure::calculateStructure($grade, $type);
                    
                    // Remove any fields that don't exist in the database
                    $cleanData = $this->cleanStructureData($structureData);
                    
                    SalaryStructure::create($cleanData);
                    $created++;
                    
                    $structures[] = [
                        'grade' => $grade,
                        'type' => $type,
                        'name' => $structureData['name'],
                        'basic_salary' => $structureData['basic_salary']
                    ];
                } else {
                    $existing++;
                }
            }
        }

        echo "Salary structures processing completed!\n";
        echo "New structures created: {$created}\n";
        echo "Existing structures: {$existing}\n";
        echo "Total structures in database: " . SalaryStructure::count() . "\n\n";

        if ($created > 0) {
            echo "Newly created structures:\n";
            foreach ($structures as $structure) {
                echo "- Grade {$structure['grade']} ({$structure['type']}): {$structure['name']} - Basic: ৳" . number_format($structure['basic_salary']) . "\n";
            }
        } else {
            echo "All salary structures already exist. No new structures created.\n";
        }
    }

    /**
     * Clean structure data to only include valid database columns
     */
    private function cleanStructureData($structureData)
    {
        // Define the valid columns that exist in your database
        $validColumns = [
            'name', 'grade_level', 'employee_type', 'basic_salary', 
            'house_rent', 'medical_allowance', 'transport_allowance', 
            'other_allowance', 'provident_fund', 'tax_deduction', 
            'other_deduction', 'overtime_rate', 'festival_bonus'
        ];

        $cleanData = [];
        foreach ($validColumns as $column) {
            if (isset($structureData[$column])) {
                $cleanData[$column] = $structureData[$column];
            }
        }

        return $cleanData;
    }
}