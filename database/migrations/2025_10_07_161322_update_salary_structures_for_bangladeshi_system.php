<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salary_structures', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('salary_structures', 'grade_level')) {
                $table->string('grade_level')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('salary_structures', 'employee_type')) {
                $table->enum('employee_type', ['permanent', 'temporary', 'contract', 'probation'])->default('permanent')->after('grade_level');
            }
            
            // Add new columns for Bangladeshi system
            if (!Schema::hasColumn('salary_structures', 'house_rent')) {
                $table->decimal('house_rent', 10, 2)->default(0)->after('medical_allowance');
            }
            
            if (!Schema::hasColumn('salary_structures', 'festival_bonus')) {
                $table->decimal('festival_bonus', 10, 2)->default(0)->after('overtime_rate');
            }
            
            // If you want to keep both house_allowance and house_rent, comment out the rename
            // If you want to replace house_allowance with house_rent, drop the old column
            if (Schema::hasColumn('salary_structures', 'house_allowance')) {
                // Option 1: Drop the old column
                $table->dropColumn('house_allowance');
                
                // Option 2: Or keep both columns by commenting the line above
            }
            
            // Similarly for bonus -> festival_bonus
            if (Schema::hasColumn('salary_structures', 'bonus')) {
                $table->dropColumn('bonus');
            }
        });
    }

    public function down()
    {
        Schema::table('salary_structures', function (Blueprint $table) {
            // Reverse the changes
            $table->dropColumn(['grade_level', 'employee_type', 'house_rent', 'festival_bonus']);
            
            // Add back the original columns if needed
            if (!Schema::hasColumn('salary_structures', 'house_allowance')) {
                $table->decimal('house_allowance', 10, 2)->default(0);
            }
            
            if (!Schema::hasColumn('salary_structures', 'bonus')) {
                $table->decimal('bonus', 10, 2)->default(0);
            }
        });
    }
};