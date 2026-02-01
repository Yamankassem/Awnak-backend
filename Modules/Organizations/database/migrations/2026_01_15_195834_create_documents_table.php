<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This will create the 'documents' table which stores files related to opportunities.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Foreign key linking the document to a specific opportunity
            $table->foreignId('opportunity_id')
                  ->constrained('opportunities')
                  ->onDelete('cascade');

            // Title or name of the document
            $table->string('title');


            $table->string('description');



            // Timestamps for created_at and updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the 'documents' table if it exists.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
