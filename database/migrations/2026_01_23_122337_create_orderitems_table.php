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
        Schema::create('orderitems', function (Blueprint $table) {
            $table->id();

            // Link to the orders table
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Product details (from fashion product form)
            $table->string('brand_name')->default('Unknown');
            $table->string('article_name')->default('Unknown');
            $table->string('type')->default('other');
            $table->string('size')->nullable();
            $table->string('fabric')->nullable();
            $table->string('gender')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);

            // Quantity
            $table->integer('quantity')->default(1);

            // Images stored as JSON
            $table->json('images')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderitems');
    }
};
