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
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('volunteer_id');
            $table->unsignedBigInteger('evaluator_id'); 
            $table->timestamp('evaluated_at');
            $table->string('improvement')->nullable();
            $table->string('strengths')->nullable();
            $table->float('score');
            $table->timestamps();

            // $table->foreignId('task_id')->constrained(tasks)->cascadeOnDelete();
            // $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
            // $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();

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
