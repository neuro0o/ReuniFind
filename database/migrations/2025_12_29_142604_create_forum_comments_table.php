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
        Schema::create('forum_comments', function (Blueprint $table) {
            $table->id('commentID');
            
            $table->foreignId('forumID')
                ->constrained('forum_posts', 'forumID')
                ->onDelete('cascade');
            
            $table->foreignId('userID')
                ->constrained('users', 'userID')
                ->onDelete('cascade');
            
            $table->text('commentText');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_comments');
    }
};
