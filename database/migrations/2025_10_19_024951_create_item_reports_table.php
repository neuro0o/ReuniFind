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
            $table->enum('reportType', [
                'Found',
                'Lost'
            ])->default('Lost');
            $table->string('itemName');
            $table->enum('itemCategory', [
                'Accessories',
                'Electronics',
                'Other'
            ])->default('Other');
            $table->string('itemDescription');
            $table->enum('itemLocation', [
                'ASTIF',
                'DKP Baru',
                'DKP Lama',
                'FIS',
                'FKI',
                'FKJ',
                'FPEP',
                'FPKS',
                'FPT',
                'FSMP',
                'FST@FSSA',
                'FSSK',
                'KKTF',
                'KKTM',
                'KKTPAR',
                'LIBRARY',
                'Other',
                'PPIB'
            ])->default('Other');
            $table->date('reportDate');
            $table->string('itemImg')->nullable();
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
