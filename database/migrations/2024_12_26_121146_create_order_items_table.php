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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();  // Primary key for the order items
            $table->foreignId('order_id')->constrained()->onDelete('cascade');  // Foreign key referencing the orders table
            $table->foreignId('product_id')->constrained()->onDelete('cascade');  // Foreign key referencing the products table
            $table->string('name');  // Product name (e.g., for display purposes)
            $table->integer('qty');  // Quantity of the product in the order
            $table->double('price', 10, 2);  // Price of a single product
            $table->double('total', 10, 2);  // Total price for the given quantity (qty * price)
        
            $table->timestamps();  // Created and updated at timestamps
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
