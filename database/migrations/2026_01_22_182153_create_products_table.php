<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Product identity
            $table->string('brand_name')->nullable();
            $table->string('article_name')->nullable();
            $table->string('type')->nullable();

            // Optional fields
            $table->json('size')->nullable();
            $table->string('fabric')->nullable();
            $table->string('gender')->nullable();

            // Common fields
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);

            // Multiple images stored as JSON
            $table->json('images')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
