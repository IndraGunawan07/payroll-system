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
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users', 'id');
            $table->foreignUuid('payroll_period_id')->nullable();
            $table->date('request_date');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('status');
            $table->dateTime('processed_at')->nullable();
            $table->boolean('is_payroll')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
    }
};
