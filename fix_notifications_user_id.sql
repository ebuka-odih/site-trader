-- Fix user_notifications table to support UUID user_id
-- Run this in your cPanel MySQL database
-- Step 1: Drop the existing foreign key constraint
ALTER TABLE user_notifications DROP FOREIGN KEY user_notifications_user_id_foreign;
-- Step 2: Change the user_id column to VARCHAR(36) to support UUIDs
ALTER TABLE user_notifications
MODIFY COLUMN user_id VARCHAR(36) NOT NULL;
-- Step 3: Re-add the foreign key constraint
ALTER TABLE user_notifications
ADD CONSTRAINT user_notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
-- Verify the change
DESCRIBE user_notifications;