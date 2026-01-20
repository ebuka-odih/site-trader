<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification for a user.
     */
    public function createNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): UserNotification {
        try {
            return UserNotification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
            ]);
            throw $e;
        }
    }

    /**
     * Create a deposit notification.
     */
    public function createDepositNotification(User $user, float $amount, string $status = 'completed'): UserNotification
    {
        $title = 'Deposit ' . ucfirst($status);
        $message = $status === 'completed' 
            ? "Your deposit of $" . number_format($amount, 2) . " has been successfully processed."
            : "Your deposit of $" . number_format($amount, 2) . " is " . $status . ".";

        return $this->createNotification(
            $user,
            'deposit',
            $title,
            $message,
            [
                'amount' => $amount,
                'status' => $status,
                'type' => 'deposit'
            ]
        );
    }

    /**
     * Create a withdrawal notification.
     */
    public function createWithdrawalNotification(User $user, float $amount, string $status = 'completed'): UserNotification
    {
        $title = 'Withdrawal ' . ucfirst($status);
        $message = $status === 'completed' 
            ? "Your withdrawal of $" . number_format($amount, 2) . " has been successfully processed."
            : "Your withdrawal of $" . number_format($amount, 2) . " is " . $status . ".";

        return $this->createNotification(
            $user,
            'withdrawal',
            $title,
            $message,
            [
                'amount' => $amount,
                'status' => $status,
                'type' => 'withdrawal'
            ]
        );
    }

    /**
     * Create a trading notification.
     */
    public function createTradingNotification(
        User $user, 
        string $action, 
        string $symbol, 
        float $amount, 
        string $status = 'completed'
    ): UserNotification {
        $title = ucfirst($action) . ' Order ' . ucfirst($status);
        $message = "Your {$action} order for {$symbol} worth $" . number_format($amount, 2) . " has been " . $status . ".";

        return $this->createNotification(
            $user,
            'trading',
            $title,
            $message,
            [
                'action' => $action,
                'symbol' => $symbol,
                'amount' => $amount,
                'status' => $status,
                'type' => 'trading'
            ]
        );
    }

    /**
     * Create a copy trading notification.
     */
    public function createCopyTradingNotification(
        User $user, 
        string $traderName, 
        float $amount, 
        string $status = 'started'
    ): UserNotification {
        $title = 'Copy Trading ' . ucfirst($status);
        $message = $status === 'started' 
            ? "You have started copying {$traderName} with $" . number_format($amount, 2) . "."
            : "Your copy trading with {$traderName} has been " . $status . ".";

        return $this->createNotification(
            $user,
            'copy_trade',
            $title,
            $message,
            [
                'trader_name' => $traderName,
                'amount' => $amount,
                'status' => $status,
                'type' => 'copy_trade'
            ]
        );
    }

    /**
     * Create a bot trading notification.
     */
    public function createBotTradingNotification(
        User $user, 
        string $botName, 
        string $action, 
        float $amount = null, 
        string $status = 'completed'
    ): UserNotification {
        $title = 'Bot Trading ' . ucfirst($action);
        $message = $amount 
            ? "Your bot '{$botName}' has {$action} with $" . number_format($amount, 2) . "."
            : "Your bot '{$botName}' has been {$action}.";

        return $this->createNotification(
            $user,
            'bot_trade',
            $title,
            $message,
            [
                'bot_name' => $botName,
                'action' => $action,
                'amount' => $amount,
                'status' => $status,
                'type' => 'bot_trade'
            ]
        );
    }

    /**
     * Create a system notification.
     */
    public function createSystemNotification(User $user, string $title, string $message, array $data = []): UserNotification
    {
        return $this->createNotification(
            $user,
            'system',
            $title,
            $message,
            array_merge($data, ['type' => 'system'])
        );
    }

    /**
     * Create a security notification.
     */
    public function createSecurityNotification(User $user, string $action, string $details = ''): UserNotification
    {
        $title = 'Security Alert';
        $message = "Security event: {$action}";
        if ($details) {
            $message .= " - {$details}";
        }

        return $this->createNotification(
            $user,
            'security',
            $title,
            $message,
            [
                'action' => $action,
                'details' => $details,
                'type' => 'security'
            ]
        );
    }

    /**
     * Create a profit/loss notification.
     */
    public function createProfitLossNotification(
        User $user, 
        string $type, 
        float $amount, 
        string $symbol = null
    ): UserNotification {
        $title = ucfirst($type) . ' Realized';
        $message = $symbol 
            ? "You have realized a {$type} of $" . number_format($amount, 2) . " on {$symbol}."
            : "You have realized a {$type} of $" . number_format($amount, 2) . ".";

        return $this->createNotification(
            $user,
            'profit_loss',
            $title,
            $message,
            [
                'type' => $type,
                'amount' => $amount,
                'symbol' => $symbol,
                'type' => 'profit_loss'
            ]
        );
    }

    /**
     * Create an admin notification.
     */
    public function createAdminNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        int $adminId = null
    ): UserNotification {
        return $this->createNotification(
            $user,
            $type,
            $title,
            $message,
            [
                'type' => $type,
                'sent_by' => 'admin',
                'admin_id' => $adminId,
                'is_admin_message' => true
            ]
        );
    }

    /**
     * Send bulk notifications to multiple users.
     */
    public function sendBulkNotifications(
        $users,
        string $type,
        string $title,
        string $message,
        int $adminId = null
    ): array {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($users as $user) {
            try {
                $notification = $this->createAdminNotification(
                    $user,
                    $type,
                    $title,
                    $message,
                    $adminId
                );
                $results[] = [
                    'user_id' => $user->id,
                    'success' => true,
                    'notification_id' => $notification->id
                ];
                $successCount++;
            } catch (\Exception $e) {
                Log::error('Failed to send bulk notification to user: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'type' => $type
                ]);
                $results[] = [
                    'user_id' => $user->id,
                    'success' => false,
                    'error' => $e->getMessage()
                ];
                $failureCount++;
            }
        }

        Log::info('Bulk notification sent', [
            'admin_id' => $adminId,
            'total_users' => count($users),
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'type' => $type
        ]);

        return [
            'results' => $results,
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'total_count' => count($users)
        ];
    }
}
