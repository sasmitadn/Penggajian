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
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_payroll');
            $table->foreign('id_payroll')->references('id')->on('payrolls')->onDelete('cascade');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price_daily', 10, 2)->default(0.00);
            $table->decimal('price_overtime', 10, 2)->default(0.00);
            $table->time('work_start')->nullable();
            $table->time('work_end')->nullable();
            $table->decimal('total_days', 10, 2)->default(0.00);
            $table->decimal('total_overtime', 10, 2)->default(0.00);
            $table->decimal('amount_salary', 10, 2)->default(0.00);
            $table->decimal('amount_overtime', 10, 2)->default(0.00);
            $table->decimal('amount_deductions', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2)->default(0.00);
            $table->string('status')->default('pending'); // 'pending', 'paid', 'cancelled'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
