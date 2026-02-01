<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Organizations Table
 *
 * Defines the schema for the organizations table, which stores
 * information about registered organizations. Each organization
 * is linked to a user account from the Core module.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the organizations table with attributes such as license number,
     * type, bio, website, and a foreign key linking to the users table.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            // Foreign key linking to the users table (Core module)
            $table->unsignedBigInteger('user_id');
            // Status default notactive and System admin can change it later 
            $table->enum('status', ['active', 'notactive'])->default('notactive');

            $table->string('license_number')->unique(); // Unique license number
            $table->string('type'); // Type of organization (NGO, school, charity, etc.)
            $table->text('bio')->nullable(); // Short description or background
            $table->string('website')->nullable(); // Official website (optional)
            $table->timestamps();

            // Foreign key relationship: each organization belongs to one user
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // Cascade delete: remove organization if user is deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the organizations table.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
