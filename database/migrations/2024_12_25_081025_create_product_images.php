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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('title', 255); // Limit to 255 characters
            $table->text('path'); // For longer paths
            $table->string('type')->nullable(); // Optional field for type (e.g., thumbnail, gallery)
            $table->string('alt_text')->nullable(); // Optional field for alt text
            $table->integer('sort_order')->default(0); // Default to 0 for sorting
            $table->boolean('is_primary')->default(false); // For primary image indication
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
