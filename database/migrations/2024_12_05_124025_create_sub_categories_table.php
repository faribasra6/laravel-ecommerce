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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name'); // Added index for performance
            $table->string('slug')->unique(); // Ensured slug is unique
            $table->integer('status')->default(0); // Similar to categories table
            $table->enum('showHome', ['Yes', 'No'])->default('No');
            $table->timestamps();
            
            // Adding an index to the category_id for better performance on relationships
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
