<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('volunteer_profiles', function (Blueprint $table) {
            $table->id();

            // Foreign IDs (stored as integers only - NO foreign key constraints)
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('location_id')->nullable();

            // Personal Information
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 20)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0); // عدد السنوات التي لديه خبرة فيها
            $table->text('previous_experience_details')->nullable(); // تفاصيل الأماكن التي عمل بها

            // Status & Verification
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance (since we can't use foreign keys)
            $table->index('user_id');
            $table->index('location_id');
            $table->index('status');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_profiles');
    }
};