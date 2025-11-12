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
        Schema::create('item_reports', function (Blueprint $table) {
            $table->id('reportID');
            $table->enum('reportType', ['Found', 'Lost'])->default('Lost');
            $table->dateTime('reportDate');
            $table->enum('reportStatus', [
                'Completed',
                'Pending', 
                'Published', 
                'Rejected'
                ])->default('Pending'); // Item report review status by admin
            
            $table->string('itemName');
            $table->foreignId('itemCategory')->constrained('item_categories', 'categoryID')->onDelete('cascade');
            $table->string('itemDescription');
            $table->foreignId('itemLocation')->constrained('item_locations', 'locationID')->onDelete('cascade');
            $table->string('itemImg')->nullable();

            // Verification + status fields
            $table->string('verificationNote'); // User’s note to support verification for item report's validity
            $table->string('verificationImg')->nullable(); // User’s uploaded proof image for item report's validity
            $table->string('rejectionNote')->nullable(); // Admin’s reason for rejecting item report from being published in the system

            $table->foreignId('userID')->constrained('users', 'userID')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_reports');
    }
};
