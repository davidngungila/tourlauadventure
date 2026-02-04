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
        Schema::create('why_travel_with_us', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "Expert Local Guides"
            $table->text('description');
            $table->unsignedBigInteger('image_id')->nullable(); // Reference to gallery
            $table->string('image_url')->nullable(); // Direct image URL
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('image_id')->references('id')->on('galleries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('why_travel_with_us');
    }
};












