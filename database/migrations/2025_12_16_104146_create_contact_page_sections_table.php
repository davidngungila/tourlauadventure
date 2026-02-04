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
        Schema::create('contact_page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // hero, contact_options, why_contact, features, etc.
            $table->string('section_name'); // Human-readable name
            $table->text('content')->nullable(); // Main content
            $table->json('data')->nullable(); // Structured data (badge, title, subtitle, etc.)
            $table->string('image_url')->nullable(); // Section image
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Create contact page features table
        Schema::create('contact_page_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->string('image_url')->nullable(); // Feature image
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_page_features');
        Schema::dropIfExists('contact_page_sections');
    }
};
