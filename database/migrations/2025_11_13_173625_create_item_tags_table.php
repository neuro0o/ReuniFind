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
        Schema::create('item_tags', function (Blueprint $table) {
            $table->id('tagID'); // Primary key, auto increment

            $table->string('tagImg'); // QR tag image path
            $table->string('itemName'); // Name of the item
            $table->string('itemImg')->nullable(); // Optional item image
            $table->foreignId('itemCategory') // Foreign key to item_categories
                ->constrained('item_categories', 'categoryID')
                ->onDelete('cascade');
            $table->string('itemDescription'); // Description of item
            $table->string('itemStatus'); // 'Safe' or 'Lost'

            $table->foreignId('userID') // Owner of the item
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
        Schema::dropIfExists('item_tags');
    }
};
