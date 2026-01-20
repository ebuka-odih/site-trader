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
        // Check if we're using MySQL or SQLite
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            // MySQL-specific operations
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'user_notifications' 
                AND COLUMN_NAME = 'user_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            // Drop any existing foreign key constraints
            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE user_notifications DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                } catch (Exception $e) {
                    // Ignore if constraint doesn't exist
                }
            }
            
            // Now modify the column
            DB::statement("ALTER TABLE user_notifications MODIFY COLUMN user_id VARCHAR(36) NOT NULL");
            
            // Re-add the foreign key constraint
            DB::statement("ALTER TABLE user_notifications ADD CONSTRAINT user_notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
        } else {
            // SQLite - recreate the table with new structure
            Schema::dropIfExists('user_notifications');
            
            Schema::create('user_notifications', function (Blueprint $table) {
                $table->id();
                $table->uuid('user_id');
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->text('data')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'read_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Change back to integer (if needed)
            $table->unsignedBigInteger('user_id')->change();
            
            // Re-add the foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
