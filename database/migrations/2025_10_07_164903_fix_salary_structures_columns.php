<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salary_structures', function (Blueprint $table) {
            // Check what columns exist and rename if needed
            if (Schema::hasColumn('salary_structures', 'house_allowance') && !Schema::hasColumn('salary_structures', 'house_rent')) {
                $table->renameColumn('house_allowance', 'house_rent');
            }
            
            if (Schema::hasColumn('salary_structures', 'bonus') && !Schema::hasColumn('salary_structures', 'festival_bonus')) {
                $table->renameColumn('bonus', 'festival_bonus');
            }
            
            // Ensure all required columns exist
            if (!Schema::hasColumn('salary_structures', 'grade_level')) {
                $table->string('grade_level')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('salary_structures', 'employee_type')) {
                $table->enum('employee_type', ['permanent', 'temporary', 'contract', 'probation'])->default('permanent')->after('grade_level');
            }
        });
    }

    public function down()
    {
        // You can reverse the changes if needed
        Schema::table('salary_structures', function (Blueprint $table) {
            if (Schema::hasColumn('salary_structures', 'house_rent') && !Schema::hasColumn('salary_structures', 'house_allowance')) {
                $table->renameColumn('house_rent', 'house_allowance');
            }
            
            if (Schema::hasColumn('salary_structures', 'festival_bonus') && !Schema::hasColumn('salary_structures', 'bonus')) {
                $table->renameColumn('festival_bonus', 'bonus');
            }
        });
    }
};