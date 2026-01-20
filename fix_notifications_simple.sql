-- Simple fix for user_notifications table
-- Run this in phpMyAdmin
-- Step 1: Check what foreign keys exist
SELECT CONSTRAINT_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'user_notifications'
    AND COLUMN_NAME = 'user_id'
    AND REFERENCED_TABLE_NAME IS NOT NULL;
-- Step 2: Drop any existing foreign key (replace 'constraint_name' with actual name from step 1)
-- ALTER TABLE user_notifications DROP FOREIGN KEY constraint_name;
-- Step 3: Change the column type
ALTER TABLE user_notifications
MODIFY COLUMN user_id VARCHAR(36) NOT NULL;
-- Step 4: Add the foreign key back
ALTER TABLE user_notifications
ADD CONSTRAINT user_notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
-- Step 5: Verify the change
DESCRIBE user_notifications;