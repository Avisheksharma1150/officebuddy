<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'employee'])->default('employee');
            $table->string('employee_id')->unique()->nullable();
            $table->date('joining_date')->nullable();
            $table->foreignId('salary_structure_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['salary_structure_id']);
            $table->dropColumn(['role', 'employee_id', 'joining_date', 'salary_structure_id']);
        });
    }
};