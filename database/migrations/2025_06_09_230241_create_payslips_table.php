<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users', 'id');
            $table->foreignUuid('payroll_period_id')->constrained('payroll_periods', 'id');
            $table->decimal('base_salary', 10, 2);
            $table->integer('total_attendance_days');
            $table->decimal('salary', 10, 2); // salary with prorate (can be full not prorated)
            $table->unsignedTinyInteger('total_overtime_hours');
            $table->decimal('overtime_pay', 10, 2);
            $table->decimal('total_reimbursements', 10, 2);
            $table->decimal('take_home_pay', 10, 2);
            $table->foreignUuid('created_by')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
