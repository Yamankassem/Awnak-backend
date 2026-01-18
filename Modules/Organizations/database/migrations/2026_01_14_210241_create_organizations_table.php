<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * * This table stores information about organizations.
     * Fields:
     * - id: Primary key
     * - license_number: Unique license number of the organization
     * - type: Type of organization (e.g., NGO, school, charity)
     * - bio: Short description or background
     * - website: Official website (optional)
     * - created_at / updated_at: Timestamps
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('license_number')->unique();
            $table->string('type');
            $table->text('bio')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
