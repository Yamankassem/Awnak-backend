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
        Schema::create('documents', function (Blueprint $table) {
     $table->id();
     $table->string('disk')->default('public'); // disk storage
    $table->string('path'); //storage path
    $table->string('original_name'); // name of the file
    $table->string('mime_type'); // type of the file (pdf, png, etc)
    $table->unsignedBigInteger('size'); // size in bytes  
    
    $table->morphs('documentable');
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
