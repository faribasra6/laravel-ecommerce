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
        Schema::create('product_rating', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('username'); // Store the username of the user giving the rating
            $table->string('email'); // Store the email of the user giving the rating
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Foreign key for the product being rated
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key for the user who gave the rating
            $table->double('rating', 8, 2); // Rating value (double type, allowing for decimals like 4.5)
            $table->text('review')->nullable(); // Optional review text
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status for admin approval
            $table->timestamps(); // Created and updated timestamps
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_rating');
    }
};
