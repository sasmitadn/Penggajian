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
        Schema::create('cash_advance_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_cash_advances');
            $table->unsignedBigInteger('id_payroll')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('payment_method')->default('auto'); // manual
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_cash_advances')->references('id')->on('cash_advances')->onDelete('cascade');
            $table->foreign('id_payroll')->references('id')->on('payrolls')->onDelete('set null'); // if null = unpaid
            // if payment_method == manual = paid
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_advance_details');
    }
};
