<?php
// Debug script to test notification system on cPanel
// Place this in your public folder and run: yourdomain.com/debug_notifications.php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;

echo "<h2>Notification System Debug</h2>";

try {
    // Test 1: Database Connection
    echo "<h3>1. Database Connection Test</h3>";
    $connection = DB::connection()->getPdo();
    echo "✅ Database connection successful<br>";
    
    // Test 2: Check if user_notifications table exists
    echo "<h3>2. Table Existence Test</h3>";
    $tableExists = DB::getSchemaBuilder()->hasTable('user_notifications');
    if ($tableExists) {
        echo "✅ user_notifications table exists<br>";
    } else {
        echo "❌ user_notifications table does NOT exist<br>";
        echo "Run: php artisan migrate<br>";
    }
    
    // Test 3: Check table structure
    if ($tableExists) {
        echo "<h3>3. Table Structure Test</h3>";
        $columns = DB::getSchemaBuilder()->getColumnListing('user_notifications');
        $requiredColumns = ['id', 'user_id', 'type', 'title', 'message', 'data', 'read_at', 'created_at', 'updated_at'];
        
        foreach ($requiredColumns as $column) {
            if (in_array($column, $columns)) {
                echo "✅ Column '{$column}' exists<br>";
            } else {
                echo "❌ Column '{$column}' is MISSING<br>";
            }
        }
    }
    
    // Test 4: Check if we have users
    echo "<h3>4. Users Test</h3>";
    $userCount = User::count();
    echo "Total users: {$userCount}<br>";
    
    if ($userCount > 0) {
        $testUser = User::first();
        echo "Test user ID: {$testUser->id}<br>";
        
        // Test 5: Try to create a notification
        echo "<h3>5. Notification Creation Test</h3>";
        try {
            $notification = $testUser->createNotification(
                'test_notification',
                'Test Notification',
                'This is a test notification to verify the system works.',
                ['test' => true]
            );
            
            if ($notification) {
                echo "✅ Notification created successfully!<br>";
                echo "Notification ID: {$notification->id}<br>";
                
                // Test 6: Check if notification was saved
                $savedNotification = UserNotification::find($notification->id);
                if ($savedNotification) {
                    echo "✅ Notification saved to database<br>";
                    echo "Title: {$savedNotification->title}<br>";
                    echo "Message: {$savedNotification->message}<br>";
                } else {
                    echo "❌ Notification not found in database<br>";
                }
            } else {
                echo "❌ Failed to create notification<br>";
            }
        } catch (Exception $e) {
            echo "❌ Error creating notification: " . $e->getMessage() . "<br>";
        }
    }
    
    // Test 7: Check existing notifications
    echo "<h3>6. Existing Notifications Test</h3>";
    $notificationCount = UserNotification::count();
    echo "Total notifications in database: {$notificationCount}<br>";
    
    if ($notificationCount > 0) {
        $recentNotifications = UserNotification::latest()->take(5)->get();
        echo "<h4>Recent Notifications:</h4>";
        foreach ($recentNotifications as $notif) {
            echo "- ID: {$notif->id}, Type: {$notif->type}, Title: {$notif->title}, Created: {$notif->created_at}<br>";
        }
    }
    
    // Test 8: Check Laravel logs
    echo "<h3>7. Log File Test</h3>";
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        echo "✅ Log file exists: {$logPath}<br>";
        $logSize = filesize($logPath);
        echo "Log file size: " . number_format($logSize) . " bytes<br>";
        
        if ($logSize > 0) {
            $lastLines = file_get_contents($logPath);
            $lines = explode("\n", $lastLines);
            $recentLines = array_slice($lines, -10);
            echo "<h4>Recent Log Entries:</h4>";
            foreach ($recentLines as $line) {
                if (trim($line)) {
                    echo htmlspecialchars($line) . "<br>";
                }
            }
        }
    } else {
        echo "❌ Log file does not exist: {$logPath}<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Critical Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h3>Environment Info</h3>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Laravel Version: " . app()->version() . "<br>";
echo "Environment: " . app()->environment() . "<br>";
echo "Debug Mode: " . (config('app.debug') ? 'ON' : 'OFF') . "<br>";
echo "Database Driver: " . config('database.default') . "<br>";
echo "Database Host: " . config('database.connections.mysql.host') . "<br>";
echo "Database Name: " . config('database.connections.mysql.database') . "<br>";
?>
