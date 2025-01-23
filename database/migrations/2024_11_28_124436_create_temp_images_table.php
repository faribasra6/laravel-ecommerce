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
        Schema::create('temp_images', function (Blueprint $table) {
            $table->id();
            $table->string('file_name'); // Store the original file name
            $table->string('file_path'); // Store the path to the file
            $table->string('file_extension'); // Store the file extension
            $table->boolean('is_active')->default(true); // Whether the image is active or in use
            $table->timestamps(); // Store created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_images');
    }
};
