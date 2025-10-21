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
                'Accessories', // Keychain | Glasses | Watch | Jewelery
                'Bags', // Backpack | Tote Bag | Luggage
                'Books & Stationery', // Notebook | Textbook | Pen | File
                'Clothing', // Jacket | Hoodie | Hat | Shoes | Uniform
                'Documents', // ID Card | IC | Passport | Exam Slip | Certificate
                'Electronics', // Phone | Laptop | Earphone | Power Bank | USB Drive
                'Keys & Cards', // House Keys | Car Keys | Access Cards
                'Other', // Anything that doesn't fit other categories
                'Personal Item' // Wallet | Purse | Cosmetics | Tumbler | Umbrella | Toiletries
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
