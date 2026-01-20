# cPanel Notification System Troubleshooting Guide

## 🚨 Common Issues & Solutions

### 1. **Database Migration Issues**
**Problem**: `user_notifications` table doesn't exist
**Solution**: 
```bash
# SSH into your cPanel or use Terminal in cPanel
cd /home/yourusername/public_html
php artisan migrate
```

### 2. **File Permissions**
**Problem**: Laravel can't write to storage/logs
**Solution**:
```bash
# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R yourusername:yourusername storage/
chown -R yourusername:yourusername bootstrap/cache/
```

### 3. **Environment Configuration**
**Problem**: Wrong database settings in `.env`
**Solution**: Check your `.env` file on cPanel:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 4. **Memory/Execution Limits**
**Problem**: PHP memory or execution time limits
**Solution**: Add to your `.htaccess` or contact hosting support:
```apache
php_value memory_limit 256M
php_value max_execution_time 300
```

### 5. **CSRF Token Issues**
**Problem**: CSRF tokens not working properly
**Solution**: Check if `APP_KEY` is set in `.env`:
```bash
php artisan key:generate
```

## 🔧 Step-by-Step Debugging

### Step 1: Run the Debug Script
1. Upload `debug_notifications.php` to your public folder
2. Visit: `https://yourdomain.com/debug_notifications.php`
3. Check all test results

### Step 2: Check Laravel Logs
```bash
# View recent logs
tail -f storage/logs/laravel.log
```

### Step 3: Test Database Connection
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Step 4: Test Notification Creation
```bash
# In tinker
>>> $user = App\Models\User::first();
>>> $user->createNotification('test', 'Test', 'Test message');
```

## 🛠️ Manual Fixes

### Fix 1: Recreate Notifications Table
```bash
# Drop and recreate table
php artisan migrate:rollback --step=1
php artisan migrate
```

### Fix 2: Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Fix 3: Check Web Server Error Logs
- Check cPanel Error Logs
- Check Apache/Nginx error logs
- Look for PHP errors

## 🔍 Specific cPanel Issues

### Issue 1: Path Problems
**Problem**: Laravel can't find files
**Solution**: Ensure your document root is set correctly in cPanel

### Issue 2: PHP Version
**Problem**: Wrong PHP version
**Solution**: 
1. Go to cPanel → Select PHP Version
2. Choose PHP 8.1 or higher
3. Enable required extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`

### Issue 3: Composer Dependencies
**Problem**: Missing dependencies
**Solution**:
```bash
composer install --optimize-autoloader --no-dev
```

## 📋 Checklist for cPanel Setup

- [ ] Database credentials are correct in `.env`
- [ ] `user_notifications` table exists
- [ ] File permissions are set correctly (755 for folders, 644 for files)
- [ ] PHP version is 8.1+
- [ ] Required PHP extensions are enabled
- [ ] Laravel caches are cleared
- [ ] `APP_KEY` is generated
- [ ] Web server can write to `storage/` directory
- [ ] Error logging is enabled

## 🚀 Quick Fix Commands

```bash
# Complete reset (run these in order)
cd /home/yourusername/public_html
php artisan down
php artisan migrate:fresh
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan up
```

## 📞 If Nothing Works

1. **Check cPanel Error Logs**: Look for specific error messages
2. **Contact Hosting Support**: They can check server-level issues
3. **Enable Debug Mode**: Set `APP_DEBUG=true` in `.env` temporarily
4. **Check PHP Error Logs**: Look for fatal errors or warnings

## 🔧 Alternative: Manual Notification Test

Create a simple test file to manually create notifications:

```php
<?php
// test_notification.php - Place in public folder
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\UserNotification;

$user = User::first();
if ($user) {
    $notification = UserNotification::create([
        'user_id' => $user->id,
        'type' => 'test',
        'title' => 'Manual Test',
        'message' => 'This notification was created manually',
        'data' => ['manual' => true]
    ]);
    echo "Notification created with ID: " . $notification->id;
} else {
    echo "No users found";
}
?>
```

Run this to test if the basic notification creation works.
