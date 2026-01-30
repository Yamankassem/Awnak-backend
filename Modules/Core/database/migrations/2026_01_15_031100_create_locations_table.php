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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();

            $table->boolean('has_coordinates')->default(false);

            // NOT NULL
            $table->geometry('coordinates', subtype: 'point', srid: 4326);
            $table->spatialIndex('coordinates');

   $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
