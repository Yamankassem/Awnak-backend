<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
                       // Spatial location column
            if (app()->environment('testing')) {
                // SQLite does not support spatial indexes → use string in testing
                $table->string('location_id')->nullable();
            } else {
                // MySQL/MariaDB supports geometry → use geometry in production
               $table->unsignedBigInteger('location_id')->nullable();
               $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');

            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            //
        });
    }
};
