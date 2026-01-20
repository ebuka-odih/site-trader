# Staking and Mining Implementation Plan

## Overview
This document tracks the implementation of staking and mining plans for the CryptBroker platform.

## Current Status
- ✅ Trading plans implemented
- ✅ Signal plans implemented  
- 🔄 Staking plans - In Progress
- ⏳ Mining plans - Pending

---

## STAKING MODULE IMPLEMENTATION

### Admin Side Tasks

#### 1. Admin Plan Controller ✅
- [x] Staking plan validation rules exist
- [x] Staking plan data preparation exists
- [x] Added currency selection for staking

#### 2. Admin Plan Model ✅
- [x] Staking fields defined: `apy_rate`, `minimum_amount`, `reward_frequency`, `lock_period`, `staking_duration`
- [x] Staking data attribute method exists
- [x] Added currency field for staking

#### 3. Admin Plan Views ✅
- [x] Staking tab exists in admin plans index
- [x] Staking form fields exist
- [x] Updated currency field to crypto options
- [x] Added currency selection dropdown

### User Side Tasks

#### 1. User Staking Controller ✅
- [x] Create `UserStakingController`
- [x] Implement `index()` - List user's staking subscriptions
- [x] Implement `create()` - Show staking plan selection
- [x] Implement `store()` - Create staking subscription
- [x] Implement `show()` - View staking details
- [x] Implement `cancel()` - Cancel staking subscription
- [x] Implement `withdraw()` - Withdraw staking rewards

#### 2. User Staking Views ✅
- [x] Create `resources/views/dashboard/staking/` directory
- [x] Create `index.blade.php` - Staking plans listing
- [x] Create `create.blade.php` - Staking subscription form
- [x] Create `show.blade.php` - Staking subscription details
- [x] Create `statistics.blade.php` - Staking statistics

#### 3. User Staking Routes ✅
- [x] Add staking routes to `routes/web.php`
- [x] Route: `user/staking` - Index
- [x] Route: `user/staking/create` - Create
- [x] Route: `user/staking/{userStaking}` - Show
- [x] Route: `user/staking/{userStaking}/cancel` - Cancel
- [x] Route: `user/staking/{userStaking}/withdraw` - Withdraw

#### 4. User Staking Model ✅
- [x] Create `UserStaking` model
- [x] Add migration for `user_stakings` table
- [x] Fields: `user_id`, `plan_id`, `amount_staked`, `currency`, `apy_rate`, `start_date`, `end_date`, `status`, `total_rewards`, `last_reward_date`

#### 5. Staking Plan Cards ✅
- [x] Design staking plan cards for user dashboard
- [x] Include: APY rate, minimum amount, lock period, currency
- [x] Add "Stake Now" button
- [x] Connect to broker links

#### 6. Bug Fixes ✅
- [x] Fixed "Start Staking" button redirect issue (JavaScript conflict resolved)
- [x] Updated navigation to use new staking routes (`user.staking.index`)
- [x] Removed old staking route (`plan.staking`) that was causing conflicts
- [x] Fixed navigation links to point to correct staking routes
- [x] Cleared all caches to ensure proper functionality

---

## MINING MODULE IMPLEMENTATION

### Admin Side Tasks ✅

#### 1. Admin Plan Controller ✅
- [x] Mining plan validation rules exist
- [x] Mining plan data preparation exists

#### 2. Admin Plan Model ✅
- [x] Mining fields defined: `hashrate`, `equipment`, `downtime`, `electricity_costs`, `mining_duration`
- [x] Mining data attribute method exists

#### 3. Admin Plan Views ✅
- [x] Mining tab exists in admin plans index
- [x] Mining form fields exist

### User Side Tasks ✅

#### 1. User Mining Controller ✅
- [x] Create `UserMiningController`
- [x] Implement `index()` - List user's mining subscriptions
- [x] Implement `create()` - Show mining plan selection
- [x] Implement `store()` - Create mining subscription
- [x] Implement `show()` - View mining details
- [x] Implement `cancel()` - Cancel mining subscription
- [x] Implement `suspend()` - Suspend mining subscription
- [x] Implement `resume()` - Resume mining subscription
- [x] Implement `withdraw()` - Withdraw mined cryptocurrency
- [x] Implement `statistics()` - Mining statistics

#### 2. User Mining Views ✅
- [x] Create `resources/views/dashboard/mining/` directory
- [x] Create `index.blade.php` - Mining plans listing and user subscriptions
- [x] Create `create.blade.php` - Mining subscription form
- [x] Create `show.blade.php` - Mining subscription details
- [x] Create `statistics.blade.php` - Mining statistics

#### 3. Mining Module Testing & Integration ✅
- [x] Test mining store functionality
- [x] Verify form submission works
- [x] Test mining plan selection
- [x] Verify database operations
- [x] Test mining status management
- [x] Fixed old route conflicts (removed `user.plan.mining`)
- [x] Updated navigation to use new mining routes (`user.mining.index`)
- [x] Cleared all caches to ensure proper functionality

#### 3. User Mining Routes ✅
- [x] Add mining routes to `routes/web.php`
- [x] Route: `user/mining` - Index
- [x] Route: `user/mining/create` - Create
- [x] Route: `user/mining/{userMining}` - Show
- [x] Route: `user/mining/{userMining}/cancel` - Cancel
- [x] Route: `user/mining/{userMining}/suspend` - Suspend
- [x] Route: `user/mining/{userMining}/resume` - Resume
- [x] Route: `user/mining/{userMining}/withdraw` - Withdraw
- [x] Route: `user/mining/statistics` - Statistics

#### 4. User Mining Model ✅
- [x] Create `UserMining` model
- [x] Add migration for `user_minings` table
- [x] Fields: `user_id`, `plan_id`, `amount_invested`, `currency`, `hashrate`, `equipment`, `downtime`, `electricity_costs`, `start_date`, `end_date`, `status`, `total_mined`, `last_mining_date`, `current_value`, `notes`
- [x] Status constants: active, completed, cancelled, suspended
- [x] Helper methods: formatting, progress calculation, ROI calculation
- [x] Scopes: active, completed, cancelled, suspended

---

## DATABASE MIGRATIONS NEEDED

### 1. Add Currency Field to Plans Table ⏳
- [ ] Add `staking_currency` field to `plans` table
- [ ] Migration: `add_staking_currency_to_plans_table`

### 2. Create User Stakings Table ⏳
- [ ] Create `user_stakings` table
- [ ] Fields: `user_id`, `plan_id`, `amount_staked`, `currency`, `apy_rate`, `start_date`, `end_date`, `status`, `total_rewards`, `last_reward_date`, `created_at`, `updated_at`

### 3. Create User Minings Table ⏳
- [ ] Create `user_minings` table
- [ ] Fields: `user_id`, `plan_id`, `hashrate`, `equipment`, `start_date`, `end_date`, `status`, `total_mined`, `last_payout_date`

---

## BROKER INTEGRATION

### Staking Broker Links ⏳
- [ ] Research popular staking platforms
- [ ] Add broker links to staking plans
- [ ] Implement referral tracking

### Mining Broker Links ⏳
- [ ] Research mining platforms
- [ ] Add broker links to mining plans
- [ ] Implement referral tracking

---

## TESTING CHECKLIST

### Staking Module
- [ ] Admin can create staking plans with crypto currency
- [ ] Users can view staking plans
- [ ] Users can subscribe to staking plans
- [ ] Users can view their staking subscriptions
- [ ] Users can cancel staking subscriptions
- [ ] Users can withdraw staking rewards

### Mining Module
- [ ] Admin can create mining plans
- [ ] Users can view mining plans
- [ ] Users can subscribe to mining plans
- [ ] Users can view their mining subscriptions

---

## NOTES
- Staking plans should use crypto currencies (BTC, ETH, USDT, etc.)
- Mining plans should include hashrate and equipment details
- Both modules need proper validation and error handling
- Consider implementing reward calculation logic
- Add proper status tracking for subscriptions
