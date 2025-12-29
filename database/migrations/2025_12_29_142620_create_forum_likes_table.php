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
        Schema::create('forum_likes', function (Blueprint $table) {
            $table->id('likeID');
            
            $table->foreignId('forumID')
                ->constrained('forum_posts', 'forumID')
                ->onDelete('cascade');
            
            $table->foreignId('userID')
                ->constrained('users', 'userID')
                ->onDelete('cascade');
            
            $table->enum('likeType', ['like', 'dislike']);
            
            $table->timestamps();
            
            // Ensure one user can only like/dislike once per post
            $table->unique(['forumID', 'userID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_likes');
    }
};
