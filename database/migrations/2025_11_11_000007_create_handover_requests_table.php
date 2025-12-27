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
        Schema::create('handover_requests', function (Blueprint $table) {
            // Primary key
            $table->id('requestID'); // Unique ID for each handover request

            /**
             * Foreign keys
             */
            // reportID: The item report that belongs to the recipient
            // This is the item that is being claimed or returned
            $table->unsignedBigInteger('reportID');

            // recipientID: The user who owns the item report and receives the handover request
            $table->unsignedBigInteger('recipientID');

            // senderReportID: The item report that belongs to the sender
            // This is the item the sender is offering to claim or return
            $table->unsignedBigInteger('senderReportID');

            // senderID: The user who initiates the handover request
            $table->unsignedBigInteger('senderID');

            /**
             * Type of request
             * 'Claim'  => The sender wants to claim the item on the recipient's found item report
             * 'Return' => The sender wants to return the item on the recipient's lost item report
             */
            $table->enum('requestType', ['Claim', 'Return']);

            /**
             * Proof of ownership or verification details
             * Can be text (description/note) or an uploaded image
             */
            $table->string('proofText')->nullable();
            $table->string('proofImg')->nullable();

            /**
             * Current status of the handover request
             * Pending   => Awaiting recipient action
             * Approved  => Recipient approved the request
             * Rejected  => Recipient rejected the request
             * Completed => Handover has been successfully completed
             */
            $table->enum('requestStatus', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending');

            // Rejection note if recipient rejects the request
            $table->string('rejectionNote')->nullable();

            // Store Handover Form Completion
            $table->string('handoverForm')->nullable();

            // created_at and updated_at
            $table->timestamps();

            $table->timestamp('sender_last_read_at')->nullable();
            $table->timestamp('recipient_last_read_at')->nullable();

            /**
             * Foreign key constraints
             * - reportID → item_reports.reportID (recipient's item)
             * - senderReportID → item_reports.reportID (sender's offered item)
             * - recipientID → users.userID
             * - senderID → users.userID
             * Cascade on delete ensures handover requests are removed if the related user or item is deleted
             */
            $table->foreign('reportID')->references('reportID')->on('item_reports')->onDelete('cascade');
            $table->foreign('senderReportID')->references('reportID')->on('item_reports')->onDelete('cascade');
            $table->foreign('recipientID')->references('userID')->on('users')->onDelete('cascade');
            $table->foreign('senderID')->references('userID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handover_requests');
    }
};
