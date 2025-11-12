<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id('categoryID');
            $table->string('categoryName')->unique();
            $table->string('description'); // Short description of the Item Category
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_categories');
    }
};
