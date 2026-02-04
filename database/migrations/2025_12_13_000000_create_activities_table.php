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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            
            // Required Fields
            $table->string('name'); // Activity Name (e.g., "Wildlife Safari")
            $table->text('description')->nullable(); // Activity Description
            $table->string('icon')->nullable(); // Font Awesome icon class (e.g., "fas fa-binoculars")
            
            // Image Fields
            $table->unsignedBigInteger('image_id')->nullable(); // Reference to gallery
            $table->string('image_url')->nullable(); // Direct image URL
            
            // Status & Display
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key
            $table->foreign('image_id')->references('id')->on('galleries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};












