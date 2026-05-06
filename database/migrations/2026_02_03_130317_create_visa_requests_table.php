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
        Schema::create('visa_requests', function (Blueprint $table) {
            $table->id();
            $table->string('image_passport')->nullable();

            $table->string('image')->nullable();

            $table->string('a_first_name')->nullable();
            $table->string('a_father')->nullable();
            $table->string('a_grand')->nullable();
            $table->string('a_family')->nullable();

            $table->string('e_first_name')->nullable();
            $table->string('e_father')->nullable();
            $table->string('e_grand')->nullable();
            $table->string('e_family')->nullable();

            $table->string('passport_number')->nullable();
            $table->string('card_id')->nullable();

            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();

            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('passport_issue_place')->nullable();
            $table->string('sex')->nullable();
            $table->string('job_or_relation_id')->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('visa_application_id')
                ->nullable()
                ->constrained('visa_applications')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_requests');
    }
};
