<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_skills', function (Blueprint $table) {
            $table->id();
            // الربط مع المتطوع
            $table->foreignId('volunteer_profile_id')
                  ->constrained('volunteer_profiles')
                  ->onDelete('cascade');
            
            // الربط مع جدول المهارات المرجعي (الجديد)
            $table->foreignId('skill_id')
                  ->constrained('skills')
                  ->onDelete('cascade');
            
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('intermediate');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_skills');
    }
};