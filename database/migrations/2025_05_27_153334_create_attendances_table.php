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
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->date('date')->default(now()->format('Y-m-d')); // Default to today's date
            $table->string('status')->nullable(); // present, absent, late, permit
            $table->time('start_time')->nullable(); // Nullable for 'absent' status
            $table->time('end_time')->nullable(); // Nullable for 'absent' status
            $table->decimal('working_hour', 10, 2)->default(0);
            $table->decimal('overtime', 10, 2)->default(0);
            $table->timestamps();

            // late dihitung otomatis dari start_time
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
