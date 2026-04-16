<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('salary_structure_id')->constrained()->onDelete('cascade');
            $table->date('month_year');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('house_allowance', 10, 2);
            $table->decimal('transport_allowance', 10, 2);
            $table->decimal('medical_allowance', 10, 2);
            $table->decimal('other_allowance', 10, 2);
            $table->decimal('tax_deduction', 10, 2);
            $table->decimal('provident_fund', 10, 2);
            $table->decimal('other_deduction', 10, 2);
            $table->decimal('overtime_earnings', 10, 2);
            $table->decimal('bonus', 10, 2);
            $table->decimal('late_deduction', 10, 2);
            $table->decimal('early_leave_deduction', 10, 2);
            $table->decimal('total_earnings', 10, 2);
            $table->decimal('total_deductions', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->enum('status', ['pending', 'processed', 'disbursed'])->default('pending');
            $table->timestamp('disbursement_date')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'month_year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
};