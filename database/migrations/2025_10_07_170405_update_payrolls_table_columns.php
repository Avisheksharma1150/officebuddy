<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Rename house_allowance to house_rent if it exists
            if (Schema::hasColumn('payrolls', 'house_allowance') && !Schema::hasColumn('payrolls', 'house_rent')) {
                $table->renameColumn('house_allowance', 'house_rent');
            }
            
            // Rename bonus to festival_bonus if it exists
            if (Schema::hasColumn('payrolls', 'bonus') && !Schema::hasColumn('payrolls', 'festival_bonus')) {
                $table->renameColumn('bonus', 'festival_bonus');
            }
            
            // Ensure house_rent column exists and is not nullable
            if (!Schema::hasColumn('payrolls', 'house_rent')) {
                $table->decimal('house_rent', 10, 2)->default(0)->after('basic_salary');
            }
            
            // Ensure festival_bonus column exists and is not nullable
            if (!Schema::hasColumn('payrolls', 'festival_bonus')) {
                $table->decimal('festival_bonus', 10, 2)->default(0)->after('overtime_earnings');
            }
        });
    }

    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Reverse the changes if needed
            if (Schema::hasColumn('payrolls', 'house_rent') && !Schema::hasColumn('payrolls', 'house_allowance')) {
                $table->renameColumn('house_rent', 'house_allowance');
            }
            
            if (Schema::hasColumn('payrolls', 'festival_bonus') && !Schema::hasColumn('payrolls', 'bonus')) {
                $table->renameColumn('festival_bonus', 'bonus');
            }
        });
    }
};