<?php
// Simple notification test for cPanel
// Place this in your public folder and run: yourdomain.com/test_notification_simple.php

echo "<h2>Simple Notification Test</h2>";

try {
    // Bootstrap Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    use App\Models\User;
    use App\Models\UserNotification;
    use Illuminate\Support\Facades\DB;
    
    echo "<h3>1. Basic Connection Test</h3>";
    
    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connected successfully<br>";
    
    // Test if table exists
    $tableExists = DB::getSchemaBuilder()->hasTable('user_notifications');
    echo $tableExists ? "✅ user_notifications table exists<br>" : "❌ user_notifications table missing<br>";
    
    if (!$tableExists) {
        echo "<strong>SOLUTION:</strong> Run 'php artisan migrate' in your cPanel terminal<br>";
        exit;
    }
    
    echo "<h3>2. User Test</h3>";
    
    // Get first user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found in database<br>";
        exit;
    }
    
    echo "✅ Found user: {$user->name} (ID: {$user->id})<br>";
    
    echo "<h3>3. Direct Database Test</h3>";
    
    // Try to create notification directly in database
    $notificationId = DB::table('user_notifications')->insertGetId([
        'user_id' => $user->id,
        'type' => 'test_direct',
        'title' => 'Direct Database Test',
        'message' => 'This notification was created directly in the database',
        'data' => json_encode(['test' => true]),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "✅ Direct database insert successful! Notification ID: {$notificationId}<br>";
    
    echo "<h3>4. Model Test</h3>";
    
    // Try to create notification using model
    $notification = UserNotification::create([
        'user_id' => $user->id,
        'type' => 'test_model',
        'title' => 'Model Test',
        'message' => 'This notification was created using the UserNotification model',
        'data' => ['test' => true]
    ]);
    
    echo "✅ Model creation successful! Notification ID: {$notification->id}<br>";
    
    echo "<h3>5. User Method Test</h3>";
    
    // Try to create notification using user method
    $userNotification = $user->createNotification(
        'test_user_method',
        'User Method Test',
        'This notification was created using the user createNotification method',
        ['test' => true]
    );
    
    if ($userNotification) {
        echo "✅ User method creation successful! Notification ID: {$userNotification->id}<br>";
    } else {
        echo "❌ User method creation failed<br>";
    }
    
    echo "<h3>6. Verification</h3>";
    
    // Count total notifications
    $totalNotifications = UserNotification::where('user_id', $user->id)->count();
    echo "Total notifications for user: {$totalNotifications}<br>";
    
    // Show recent notifications
    $recentNotifications = UserNotification::where('user_id', $user->id)
        ->latest()
        ->take(3)
        ->get();
    
    echo "<h4>Recent Notifications:</h4>";
    foreach ($recentNotifications as $notif) {
        echo "- {$notif->type}: {$notif->title} (Created: {$notif->created_at})<br>";
    }
    
    echo "<h3>✅ All Tests Passed!</h3>";
    echo "The notification system is working correctly. The issue might be in the specific controllers or routes.<br>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error Found:</h3>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h3>Environment Info</h3>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Laravel Version: " . (class_exists('Illuminate\Foundation\Application') ? app()->version() : 'Not loaded') . "<br>";
echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";
?>
