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
        Schema::create('cloudinary_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Account name/identifier
            $table->string('cloud_name');
            $table->string('api_key');
            $table->string('api_secret');
            $table->string('cloudinary_url')->nullable(); // Full URL if provided
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false); // Default account to use
            $table->text('description')->nullable();
            $table->json('settings')->nullable(); // Additional settings
            $table->timestamp('last_connection_test')->nullable();
            $table->boolean('connection_status')->default(false); // Last test result
            $table->text('connection_error')->nullable(); // Last error if any
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('is_active');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloudinary_accounts');
    }
};
