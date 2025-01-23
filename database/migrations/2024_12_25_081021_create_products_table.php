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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('untitled');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('shipping_returns')->nullable();
            $table->text('related_products')->nullable();
            $table->double('price', 10, 2)->unsigned();
            $table->double('compare_price', 10, 2)->unsigned()->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()->constrained('sub_categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_featured')->default(false);
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->boolean('track_qty')->default(false);
            $table->integer('qty')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
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
