<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id(); // Primary key (auto-increment)

            $table->string('title'); // Opportunity title
            $table->text('description')->nullable(); // Detailed description of the opportunity
            $table->string('type')->nullable(); // Type of opportunity (volunteering, training, job, etc.)
            $table->date('start_date')->nullable(); // Opportunity start date
            $table->date('end_date')->nullable(); // Opportunity end date

            $table->unsignedBigInteger('organization_id'); // Foreign key linking to organizations table

            $table->timestamps(); // created_at and updated_at columns

            // Foreign key relationship: each opportunity belongs to one organization
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('organizations')
                  ->onDelete('cascade'); // Cascade delete: remove opportunities if organization is deleted
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities'); // Rollback: drop the opportunities table
    }
};
