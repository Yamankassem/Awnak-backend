<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_availability', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('volunteer_profile_id');
            
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            
            $table->timestamps();
            
            // Foreign key constraint (internal relationship - allowed)
            $table->foreign('volunteer_profile_id')
                  ->references('id')
                  ->on('volunteer_profiles')
                  ->onDelete('cascade');
            
            // Index for queries
            $table->index('day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_availability');
    }
};