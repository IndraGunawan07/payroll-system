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
        Schema::create('payslip_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('payslip_id')->constrained('payslips', 'id');
            $table->string('type'); // attendance, overtime, reimbursement
            $table->decimal('amount', 10, 2);
            $table->text('formula');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslip_details');
    }
};
