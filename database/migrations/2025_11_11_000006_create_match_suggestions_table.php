<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_suggestions', function (Blueprint $table) {
            $table->id('suggestionID');
            $table->unsignedBigInteger('reportID');          // The report created by the user
            $table->unsignedBigInteger('matchedReportID');   // The matching report found
            $table->unsignedBigInteger('userID');            // The user who owns this suggestion (the one who generates the matches)
            $table->enum('matchStatus',[
                'suggested', // auto-generated matches
                'pending', // user has started a handover request for this pair
                'accepted', // pair is approved during handover request
                'completed', // item handover completed
                'dismissed' // user explicitly dismissed the suggestion or pair is rejected during handover request
                ])->default('suggested');
            $table->timestamp('matchedAt')->nullable();       // When this match was generated
            $table->timestamps();                             // created_at, updated_at

            // Foreign keys
            $table->foreign('reportID')->references('reportID')->on('item_reports')->onDelete('cascade');
            $table->foreign('matchedReportID')->references('reportID')->on('item_reports')->onDelete('cascade');
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_suggestions');
    }
};
