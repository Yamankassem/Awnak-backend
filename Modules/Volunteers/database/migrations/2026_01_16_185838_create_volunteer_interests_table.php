<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_profile_id')
                  ->constrained('volunteer_profiles')
                  ->onDelete('cascade');

            $table->foreignId('interest_id')
                  ->constrained('interests')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_interests');
    }
};