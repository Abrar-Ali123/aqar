<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScheduledBackupDatabase extends Command
{
    protected $signature = 'backup:scheduled';
    protected $description = 'Create a scheduled database backup';

    public function handle()
    {
        $filename = 'backup_' . now()->format('Y_m_d_His') . '.sql';
        $path = storage_path('backups/' . $filename);
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            $path
        );
        exec($command);
        $this->info('Backup created at ' . $path);
    }
}
