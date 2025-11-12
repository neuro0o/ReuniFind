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

            $table->unsignedBigInteger('requestID'); // Related Handover Request
            $table->unsignedBigInteger('senderID');  // User who sent this message

            $table->string('messageText')->nullable();      // Text content of the message
            $table->string('messageImg')->nullable(); // Image content of the message

            $table->dateTime('created_at'); // Timestamp for when this message is created and sent

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
