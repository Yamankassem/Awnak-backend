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
        Schema::create('evaluations', function (Blueprint $table) {
           $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('score'); //  1 - 5
            $table->string('strengths')->nullable();
            $table->string('improvement')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
