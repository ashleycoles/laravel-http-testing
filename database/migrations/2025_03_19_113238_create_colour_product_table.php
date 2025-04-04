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
        Schema::create('colour_product', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Colour::class);
            $table->foreignIdFor(\App\Models\Product::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colour_product');
    }
};
