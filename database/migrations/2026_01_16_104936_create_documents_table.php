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
        $table->string('file_path'); // storage path
        $table->string('file_name'); // name of the file
        $table->string('file_type'); // (pdf, png, etc)
        
        $table->unsignedBigInteger('documentable_id'); 
        $table->string('documentable_type'); 

        $table->unsignedBigInteger('uploaded_by'); // 
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
