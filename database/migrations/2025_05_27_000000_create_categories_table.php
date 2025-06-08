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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // id 1 owner
            $table->string('name')->nullable();
            $table->string('type')->nullable(); // user_category, job_category, etc.
            $table->text('role')->nullable();
            $table->integer('is_paid')->default(0);
            $table->decimal('price_daily', 10, 2)->default(0.00);
            $table->decimal('price_overtime', 10, 2)->default(0.00);
            $table->time('work_start')->nullable();
            $table->time('work_end')->nullable();
            $table->string('status')->default('active'); // 'active', 'inactive'.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
