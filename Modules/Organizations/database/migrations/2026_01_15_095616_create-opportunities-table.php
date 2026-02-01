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
 * - In production (MySQL), the 'coordinates' column can be defined as a geometry type.
 * - In testing (SQLite), spatial columns may be simplified to avoid errors.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Creates the opportunities table with attributes such as title,
     * description, type, start/end dates, status, organization linkage,
     * and timestamps.
     *
     * Columns:
     * - id: Primary key (auto-increment)
     * - title: Opportunity title
     * - description: Detailed description (nullable)
     * - type: Type of opportunity (volunteering, training, job, etc.)
     * - start_date: Opportunity start date (nullable)
     * - end_date: Opportunity end date (nullable)
     * - status: Enum (approved, rejected, pending), default 'pending'
     * - organization_id: Foreign key linking to organizations table
     * - timestamps: created_at and updated_at
     *
     * Relationships:
     * - organization_id references organizations.id (cascade on delete)
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
            $table->enum('status', ['approved', 'rejected', 'pending'])->default('pending'); // Status of Opportunity

            $table->unsignedBigInteger('organization_id'); // Foreign key to organizations

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
