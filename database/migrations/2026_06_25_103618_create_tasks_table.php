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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('judul_tugas');
            $table->text('deskripsi')->nullable();
            $table->dateTime('deadline');
            
            $table->foreignId('status_id')->default(1)->constrained('statuses')->restrictOnDelete();
            $table->foreignId('priority_id')->default(2)->constrained('task_priorities')->restrictOnDelete();
            $table->timestamps();

            $table->index(['course_id', 'deadline']);
            $table->index(['status_id', 'priority_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
