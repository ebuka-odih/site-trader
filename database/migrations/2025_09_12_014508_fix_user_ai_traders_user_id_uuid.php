<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use try-catch to handle the case where foreign key doesn't exist
        try {
            Schema::table('user_ai_traders', function (Blueprint $table) {
                // Try to drop the existing foreign key constraint and column
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        } catch (Exception $e) {
            // If foreign key doesn't exist, just try to drop the column
            try {
                Schema::table('user_ai_traders', function (Blueprint $table) {
                    $table->dropColumn('user_id');
                });
            } catch (Exception $e2) {
                // Column might not exist, continue
            }
        }

        // Check if user_id column exists and what type it is
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            $columns = DB::select("SHOW COLUMNS FROM user_ai_traders LIKE 'user_id'");
        } else {
            // SQLite
            $columns = DB::select("PRAGMA table_info(user_ai_traders)");
            $columns = array_values(array_filter($columns, function($col) {
                return $col->name === 'user_id';
            }));
        }
        
        if (empty($columns)) {
            // Column doesn't exist, add it
            Schema::table('user_ai_traders', function (Blueprint $table) {
                $table->uuid('user_id')->nullable()->after('id');
            });
        } else {
            // Column exists, check if it's already UUID type
            $column = reset($columns); // Get first element
            $isUuid = false;
            
            if ($driver === 'mysql') {
                $isUuid = strpos($column->Type, 'char(36)') !== false || strpos($column->Type, 'varchar(36)') !== false;
                if (!$isUuid) {
                    DB::statement('ALTER TABLE user_ai_traders MODIFY COLUMN user_id CHAR(36) NULL');
                }
            } else {
                // SQLite - just recreate the table if needed
                $isUuid = $column->type === 'TEXT';
                if (!$isUuid) {
                    Schema::table('user_ai_traders', function (Blueprint $table) {
                        $table->uuid('user_id')->nullable()->change();
                    });
                }
            }
        }

        // Check if foreign key already exists
        if ($driver === 'mysql') {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'user_ai_traders' 
                AND COLUMN_NAME = 'user_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
        } else {
            // SQLite - check if foreign key exists
            $foreignKeys = DB::select("PRAGMA foreign_key_list(user_ai_traders)");
            $foreignKeys = array_filter($foreignKeys, function($fk) {
                return $fk->from === 'user_id' && $fk->table === 'users';
            });
        }

        if (empty($foreignKeys)) {
            Schema::table('user_ai_traders', function (Blueprint $table) {
                // Add foreign key constraint
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Check if indexes exist and add them if they don't
        if ($driver === 'mysql') {
            $indexes = DB::select("SHOW INDEX FROM user_ai_traders WHERE Column_name = 'user_id'");
            $hasUserStatusIndex = false;
            $hasUniqueIndex = false;
            
            foreach ($indexes as $index) {
                if ($index->Key_name === 'user_ai_traders_user_id_status_index') {
                    $hasUserStatusIndex = true;
                }
                if (strpos($index->Key_name, 'user_ai_traders_user_id_ai_trader_id_status_unique') !== false) {
                    $hasUniqueIndex = true;
                }
            }
        } else {
            // SQLite - just add the indexes
            $hasUserStatusIndex = false;
            $hasUniqueIndex = false;
        }

        if (!$hasUserStatusIndex) {
            try {
                Schema::table('user_ai_traders', function (Blueprint $table) {
                    $table->index(['user_id', 'status']);
                });
            } catch (Exception $e) {
                // Index might already exist, continue
            }
        }

        if (!$hasUniqueIndex) {
            try {
                Schema::table('user_ai_traders', function (Blueprint $table) {
                    $table->unique(['user_id', 'ai_trader_id', 'status']);
                });
            } catch (Exception $e) {
                // Index might already exist, continue
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('user_ai_traders', function (Blueprint $table) {
                // Drop the UUID foreign key constraint and column
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        } catch (Exception $e) {
            // If foreign key doesn't exist, just try to drop the column
            try {
                Schema::table('user_ai_traders', function (Blueprint $table) {
                    $table->dropColumn('user_id');
                });
            } catch (Exception $e2) {
                // Column might not exist, continue
            }
        }

        Schema::table('user_ai_traders', function (Blueprint $table) {
            // Add back the UUID user_id column
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->unique(['user_id', 'ai_trader_id', 'status']);
        });
    }
};