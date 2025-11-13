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
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id('forumID'); // Primary key, auto increment

            $table->enum('forumCategory', ['Personal Story', 'Tips & Tricks', 'Others']); // Category
            $table->string('forumTitle');   // Title of the forum post
            $table->string('forumContent'); // Content of the forum post
            $table->string('forumImg')->nullable(); // Optional image attachment
            $table->dateTime('forumDate'); // Date forum was posted

            $table->foreignId('userID') // Author of the post
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
        Schema::dropIfExists('forum_posts');
    }
};
