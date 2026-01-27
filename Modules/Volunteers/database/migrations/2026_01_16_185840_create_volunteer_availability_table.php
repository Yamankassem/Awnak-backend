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
            $table->foreignId('volunteer_profile_id')
                ->constrained('volunteer_profiles')
                ->cascadeOnDelete();

            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();

            // Index for queries
            $table->index('day');
            $table->index('volunteer_profile_id');

            // Prevent duplicate identical availability slots
            $table->unique(
                ['volunteer_profile_id', 'day', 'start_time', 'end_time'],
                'volunteer_availability_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_availability');
    }
};
