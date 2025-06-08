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
        Schema::create('cash_advances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->date('date')->nullable();
            $table->integer('is_credit')->default(0);
            $table->integer('credit_count')->default(0);
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->timestamps();
            
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_advances');
    }
};
