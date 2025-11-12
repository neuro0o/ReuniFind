<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_locations', function (Blueprint $table) {
            $table->id('locationID');
            $table->string('locationName')->unique();
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_locations');
    }
};
