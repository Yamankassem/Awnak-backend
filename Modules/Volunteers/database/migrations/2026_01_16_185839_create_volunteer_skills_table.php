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
            $table->unsignedBigInteger('volunteer_profile_id');
            
            $table->string('skill_name', 100);
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('intermediate');
            
            $table->timestamps();
            
            // Foreign key constraint (internal relationship - allowed)
            $table->foreign('volunteer_profile_id')
                  ->references('id')
                  ->on('volunteer_profiles')
                  ->onDelete('cascade');
            
            // Index for queries
            $table->index('skill_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_skills');
    }
};