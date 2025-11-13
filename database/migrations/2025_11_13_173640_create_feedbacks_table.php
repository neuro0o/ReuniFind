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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id('feedbackID'); // Primary key, auto increment

            $table->enum('feedbackType', ['Error/Bug Report', 'Review', 'Suggestion']); // Feedback type
            $table->enum('feedbackStatus', ['Pending', 'Reviewed'])->default('Pending'); // Feedback status
            $table->string('feedbackText'); // Content of feedback
            $table->dateTime('feedbackDate'); // Date feedback submitted

            $table->foreignId('userID') // Owner of the feedback
                ->constrained('users', 'userID')
                ->onDelete('cascade');

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
