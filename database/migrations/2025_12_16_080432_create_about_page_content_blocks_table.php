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
        Schema::create('about_page_content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('block_type'); // culture, sustainability, partnerships, location, social_responsibility, etc.
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('content')->nullable();
            $table->text('description')->nullable(); // Short description
            $table->unsignedBigInteger('image_id')->nullable(); // Reference to gallery
            $table->string('image_url')->nullable(); // Direct image URL
            $table->json('images')->nullable(); // Multiple images
            $table->json('data')->nullable(); // Additional structured data
            $table->string('icon')->nullable(); // Icon class
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('image_id')->references('id')->on('galleries')->onDelete('set null');
            $table->index(['block_type', 'is_active', 'display_order'], 'apcb_type_active_order_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_page_content_blocks');
    }
};
