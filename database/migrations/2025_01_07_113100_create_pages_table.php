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
        Schema::create('pages', function (Blueprint $table) {
            $table->id(); // Primary key for the page
            $table->string('title'); // The title of the page
            $table->string('slug')->unique(); // Slug for SEO-friendly URLs
            $table->text('content')->nullable(); // Content of the page (could be HTML or Markdown)
            $table->enum('status', ['active', 'inactive'])->default('active'); // Page status
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
