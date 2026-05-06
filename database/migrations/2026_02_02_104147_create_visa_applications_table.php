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
        Schema::create('visa_applications', function (Blueprint $table) {
            $table->id();

            // بيانات الكفيل
            $table->string('sponsor_full_name')->nullable();
            $table->string('sponsor_identity_number')->nullable();

            // بيانات التأشيرة
            $table->string('visa_type')->nullable();
            $table->string('visa_number')->nullable();

            // القنصلية
            $table->string('consulate_name')->nullable();

            // حالة الطلب
            $table->string('status')->default('pending');
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_applications');
    }
};
