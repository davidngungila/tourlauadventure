<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tables have already been dropped manually
        // This migration is now a no-op to mark it as complete
        $tables = ['model_has_permissions', 'model_has_roles', 'role_permission', 'role_user', 'permissions', 'roles'];
        $allDropped = true;
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $allDropped = false;
                break;
            }
        }
        
        // If any tables still exist, try to drop them (shouldn't happen)
        if (!$allDropped) {
            $driver = DB::getDriverName();
            
            // Disable foreign key checks for MySQL/MariaDB
            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF');
            }
            
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    try {
                        Schema::dropIfExists($table);
                    } catch (\Exception $e) {
                        // Ignore errors
                    }
                }
            }
            
            // Re-enable foreign key checks
            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        }
    }

    public function down(): void
    {
        // This migration is one-way
    }
};

