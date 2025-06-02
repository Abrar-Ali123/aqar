<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // جدولة النسخ الاحتياطي اليومي
        $schedule->command('backup:database')
                ->daily()
                ->at('01:00')
                ->appendOutputTo(storage_path('logs/backup.log'));

        // نسخ احتياطي سحابي أسبوعي
        $schedule->command('backup:database --store=cloud')
                ->weekly()
                ->sundays()
                ->at('02:00')
                ->appendOutputTo(storage_path('logs/backup-cloud.log'));

        // تنظيف السجلات القديمة
        $schedule->command('log:clear')
                ->monthly()
                ->appendOutputTo(storage_path('logs/maintenance.log'));

        // جدولة إرسال إشعارات البحث
        $schedule->command('search:notify')
                ->hourly()
                ->withoutOverlapping();

        // تنظيف الصلاحيات المؤقتة المنتهية
        $schedule->command('permissions:cleanup')
                ->hourly()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/permissions.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
