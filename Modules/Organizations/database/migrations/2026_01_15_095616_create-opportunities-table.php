<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Opportunities Table
 *
 * Defines the schema for the opportunities table, which stores
 * information about volunteer, training, or job opportunities.
 * Each opportunity belongs to a single organization and may
 * include spatial location data for advanced queries.
 *
 * Notes:
 * - In production (MySQL), the 'coordinates' column is created as a geometry type.
 * - In testing (SQLite), the 'coordinates' column is created as a string
 *   to avoid errors since SQLite does not support spatial indexes.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Creates the opportunities table with attributes such as title,
     * description, type, start/end dates, status, organization linkage,
     * and spatial location.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id(); // Primary key (auto-increment)

            $table->string('title'); // Opportunity title
            $table->text('description')->nullable(); // Detailed description
            $table->string('type')->nullable(); // Type (volunteering, training, job, etc.)
            $table->date('start_date')->nullable(); // Start date
            $table->date('end_date')->nullable(); // End date
            $table->enum('status', ['approved', 'rejected', 'pending'])->default('pending'); // Status

            $table->unsignedBigInteger('organization_id'); // Foreign key to organizations

            // Spatial location column
            if (app()->environment('testing')) {
                // SQLite does not support spatial indexes → use string in testing
                $table->string('coordinates')->nullable();
            } else {
                // MySQL/MariaDB supports geometry → use geometry in production
                $table->geometry('coordinates')->nullable();
            }

            $table->timestamps(); // created_at and updated_at

            // Foreign key relationship: each opportunity belongs to one organization
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('organizations')
                  ->onDelete('cascade'); // Cascade delete if organization is removed
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the opportunities table.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
