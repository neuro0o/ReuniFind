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
        Schema::create('handover_messages', function (Blueprint $table) {
            $table->id('messageID'); // Primary key

            $table->unsignedBigInteger('requestID'); // Link to handover_requests
            $table->unsignedBigInteger('senderID');  // Who sent this message

            $table->string('message')->nullable();      // Text content
            $table->string('messageImage')->nullable(); // Optional image (proof/photo)

            $table->timestamps(); // created_at and updated_at

            /**
             * Foreign key constraints
             * - requestID → handover_requests.requestID
             * - senderID → users.userID
             */
            $table->foreign('requestID')->references('requestID')->on('handover_requests')->onDelete('cascade');
            $table->foreign('senderID')->references('userID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handover_messages');
    }
};
