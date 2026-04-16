<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('house_allowance', 10, 2)->default(0);
            $table->decimal('transport_allowance', 10, 2)->default(0);
            $table->decimal('medical_allowance', 10, 2)->default(0);
            $table->decimal('other_allowance', 10, 2)->default(0);
            $table->decimal('tax_deduction', 5, 2)->default(0);
            $table->decimal('provident_fund', 5, 2)->default(0);
            $table->decimal('other_deduction', 10, 2)->default(0);
            $table->decimal('overtime_rate', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->timestamps();
            // In your migration file
$table->decimal('festival_bonus', 10, 2)->default(0.00)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_structures');
    }
};