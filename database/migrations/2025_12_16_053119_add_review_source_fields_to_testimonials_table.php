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
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'source')) {
                $table->string('source')->nullable()->after('author_image_url')->default('website');
                // Options: 'website', 'google', 'tripadvisor', 'facebook', 'other'
            }
            if (!Schema::hasColumn('testimonials', 'review_url')) {
                $table->string('review_url')->nullable()->after('source');
            }
            if (!Schema::hasColumn('testimonials', 'review_date')) {
                $table->date('review_date')->nullable()->after('review_url');
            }
            if (!Schema::hasColumn('testimonials', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('testimonials', 'review_url')) {
                $table->dropColumn('review_url');
            }
            if (Schema::hasColumn('testimonials', 'review_date')) {
                $table->dropColumn('review_date');
            }
            if (Schema::hasColumn('testimonials', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });
    }
};
