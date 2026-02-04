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
        Schema::table('tours', function (Blueprint $table) {
            if (!Schema::hasColumn('tours', 'is_last_minute_deal')) {
                $table->boolean('is_last_minute_deal')->default(false)->after('is_featured');
            }
            
            if (!Schema::hasColumn('tours', 'last_minute_discount_percentage')) {
                $table->decimal('last_minute_discount_percentage', 5, 2)->nullable()->after('is_last_minute_deal');
            }
            
            if (!Schema::hasColumn('tours', 'last_minute_deal_expires_at')) {
                $table->timestamp('last_minute_deal_expires_at')->nullable()->after('last_minute_discount_percentage');
            }
            
            if (!Schema::hasColumn('tours', 'last_minute_original_price')) {
                $table->decimal('last_minute_original_price', 10, 2)->nullable()->after('last_minute_deal_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 'is_last_minute_deal')) {
                $table->dropColumn('is_last_minute_deal');
            }
            
            if (Schema::hasColumn('tours', 'last_minute_discount_percentage')) {
                $table->dropColumn('last_minute_discount_percentage');
            }
            
            if (Schema::hasColumn('tours', 'last_minute_deal_expires_at')) {
                $table->dropColumn('last_minute_deal_expires_at');
            }
            
            if (Schema::hasColumn('tours', 'last_minute_original_price')) {
                $table->dropColumn('last_minute_original_price');
            }
        });
    }
};
