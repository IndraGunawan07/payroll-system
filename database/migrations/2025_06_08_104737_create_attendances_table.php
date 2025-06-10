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
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users', 'id');
            $table->foreignUuid('payroll_period_id')->nullable();
            $table->date('check_in_date');
            $table->timestamp('check_in_time');
            $table->dateTime('processed_at')->nullable();
            $table->boolean('is_payroll')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'check_in_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
