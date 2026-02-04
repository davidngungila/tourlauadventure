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
        Schema::table('notification_providers', function (Blueprint $table) {
            $table->string('sms_bearer_token')->nullable()->after('sms_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_providers', function (Blueprint $table) {
            $table->dropColumn('sms_bearer_token');
        });
    }
};



