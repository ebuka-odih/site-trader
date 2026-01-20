# cPanel Laravel Scheduler Setup Guide

## Overview
This guide explains how to set up the Laravel scheduler in cPanel to run your trade simulation commands automatically.

## What We've Added

### 1. Trade Market Simulation Command
- **Command**: `trade:simulate-market`
- **Purpose**: Simulates market activities including:
  - Price movements for trade pairs
  - Random trade creation
  - P&L updates for existing trades
- **Frequency**: Every 5 minutes

### 2. Bot Trading Simulation Command
- **Command**: `bot-trading:simulate`
- **Purpose**: Simulates bot trading activities
- **Frequency**: Every minute

### 3. Price Updates
- **Command**: `prices:update-scheduled`
- **Purpose**: Updates asset prices
- **Frequency**: Every 30 seconds

## cPanel Setup Instructions

### Step 1: Access cPanel Cron Jobs
1. Log into your cPanel
2. Find "Cron Jobs" in the Advanced section
3. Click on "Cron Jobs"

### Step 2: Add the Laravel Scheduler Cron Job
1. **Common Settings**: Select "Every 5 Minutes" (or "Every Minute" for more frequent updates)
2. **Command**: Replace the command with:
   ```bash
   cd /home/username/public_html && php artisan schedule:run
   ```
   **Note**: Replace `/home/username/public_html` with your actual path to the Laravel project

3. **Example paths** (adjust based on your setup):
   - **Standard cPanel**: `/home/username/public_html`
   - **Subdomain**: `/home/username/public_html/subdomain`
   - **Custom directory**: `/home/username/public_html/laravel-project`

### Step 3: Verify the Path
To find your exact path:
1. In cPanel, go to "File Manager"
2. Navigate to your Laravel project folder
3. Look at the path in the address bar
4. Use that exact path in the cron job

### Step 4: Test the Setup
1. **Manual Test**: Run this command in cPanel Terminal or SSH:
   ```bash
   php artisan schedule:run
   ```
2. **Check Logs**: Monitor your Laravel logs for any errors
3. **Verify Results**: Check if trade pairs are getting updated prices

## Alternative: More Frequent Updates

If you want more frequent updates, you can set the cron job to run every minute:

```bash
cd /home/username/public_html && php artisan schedule:run
```

**Cron Setting**: `* * * * *` (Every minute)

## What Happens When Scheduler Runs

### Every 5 Minutes:
- ✅ **Trade Market Simulation**: Updates prices, creates demo trades, updates P&L
- ✅ **Bot Trading Simulation**: Simulates bot trading activities

### Every Minute:
- ✅ **Bot Trading Simulation**: Simulates bot trading activities
- ✅ **Trade Duration Check**: Checks and updates trade durations

### Every 30 Seconds:
- ✅ **Price Updates**: Updates asset prices from external APIs

## Troubleshooting

### Common Issues:
1. **Path not found**: Double-check your project path in cPanel
2. **Permission denied**: Ensure the cron job has proper permissions
3. **PHP not found**: Use full path to PHP if needed: `/usr/bin/php artisan schedule:run`

### Check Logs:
- Laravel logs: `storage/logs/laravel.log`
- cPanel error logs: Check cPanel error logs section

### Test Commands:
```bash
# Test individual commands
php artisan trade:simulate-market
php artisan bot-trading:simulate
php artisan prices:update-scheduled

# Test scheduler
php artisan schedule:run
```

## Benefits of This Setup

1. **Automated Trading Simulation**: Your platform will have realistic market activity
2. **No Manual Intervention**: Everything runs automatically via cron
3. **Realistic Data**: Users see price movements and trade updates
4. **Demo Environment**: Perfect for testing and demonstration purposes

## Security Note

- The simulation commands are safe to run in production
- They only create demo data and don't affect real user accounts
- All activities are logged for monitoring purposes
