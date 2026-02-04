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
        if (!Schema::hasTable('about_page_content_blocks')) {
            return;
        }
        
        // Check if index already exists
        try {
            Schema::table('about_page_content_blocks', function (Blueprint $table) {
                $table->index(['block_type', 'is_active', 'display_order'], 'apcb_type_active_order_idx');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore error
            if (strpos($e->getMessage(), 'Duplicate key name') === false && 
                strpos($e->getMessage(), 'already exists') === false) {
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_page_content_blocks', function (Blueprint $table) {
            //
        });
    }
};
