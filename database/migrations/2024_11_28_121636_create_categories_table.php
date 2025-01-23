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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ensure name is unique
            $table->string('slug'); // Removed unique constraint as specified
            $table->string('image')->nullable();
            $table->boolean('status')->default(false); 
            $table->enum('showHome', ['Yes', 'No'])->default('No');; // Added showHome column with index
            $table->timestamps();
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
