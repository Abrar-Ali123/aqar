<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SystemNotification;
use App\Notifications\EmailNotification;
use App\Notifications\PushNotification;

class NotificationService
{
    protected $loggingService;

    public function __construct(LoggingService $loggingService)
    {
        $this->loggingService = $loggingService;
    }

    /**
     * إرسال إشعار للمستخدم
     *
     * @param User $user
     * @param string $title
     * @param string $message
     * @param array $data
     * @param array $channels
     * @return void
     */
    public function sendToUser(
        User $user,
        string $title,
        string $message,
        array $data = [],
        array $channels = ['database', 'mail']
    ): void {
        try {
            $notification = new SystemNotification($title, $message, $data);

            foreach ($channels as $channel) {
                switch ($channel) {
                    case 'database':
                        $user->notify($notification);
                        break;
                    case 'mail':
                        Notification::send($user, new EmailNotification($title, $message, $data));
                        break;
                    case 'push':
                        Notification::send($user, new PushNotification($title, $message, $data));
                        break;
                }
            }

            $this->loggingService->info('Notification sent', [
                'user_id' => $user->id,
                'title' => $title,
                'channels' => $channels
            ]);
        } catch (\Exception $e) {
            $this->loggingService->error('Failed to send notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * إرسال إشعار لمجموعة من المستخدمين
     *
     * @param array $users
     * @param string $title
     * @param string $message
     * @param array $data
     * @param array $channels
     * @return void
     */
    public function sendToUsers(
        array $users,
        string $title,
        string $message,
        array $data = [],
        array $channels = ['database', 'mail']
    ): void {
        foreach ($users as $user) {
            $this->sendToUser($user, $title, $message, $data, $channels);
        }
    }

    /**
     * إرسال إشعار لجميع المستخدمين
     *
     * @param string $title
     * @param string $message
     * @param array $data
     * @param array $channels
     * @return void
     */
    public function broadcast(
        string $title,
        string $message,
        array $data = [],
        array $channels = ['database']
    ): void {
        $users = User::all();
        $this->sendToUsers($users->all(), $title, $message, $data, $channels);
    }

    /**
     * إرسال إشعار للمشرفين
     *
     * @param string $title
     * @param string $message
     * @param array $data
     * @param array $channels
     * @return void
     */
    public function notifyAdmins(
        string $title,
        string $message,
        array $data = [],
        array $channels = ['database', 'mail']
    ): void {
        $admins = User::where('role', 'admin')->get();
        $this->sendToUsers($admins->all(), $title, $message, $data, $channels);
    }

    /**
     * إرسال إشعار طوارئ
     *
     * @param string $title
     * @param string $message
     * @param array $data
     * @return void
     */
    public function sendEmergencyNotification(
        string $title,
        string $message,
        array $data = []
    ): void {
        $this->notifyAdmins(
            "🚨 {$title}",
            $message,
            array_merge($data, ['priority' => 'high']),
            ['database', 'mail', 'push']
        );

        $this->loggingService->critical('Emergency notification sent', [
            'title' => $title,
            'message' => $message
        ]);
    }

    /**
     * تنظيف الإشعارات القديمة
     *
     * @param int $days
     * @return int
     */
    public function cleanOldNotifications(int $days = 30): int
    {
        $count = DB::table('notifications')
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        $this->loggingService->info('Old notifications cleaned', [
            'days' => $days,
            'count' => $count
        ]);

        return $count;
    }
}
