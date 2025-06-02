<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--store=local}';
    protected $description = 'Create a backup of the database';

    private $backupPath = 'backups/database';
    private $keepBackups = 7; // عدد النسخ الاحتياطية التي سيتم الاحتفاظ بها

    public function handle()
    {
        $this->info('بدء عملية النسخ الاحتياطي...');

        try {
            // إنشاء اسم الملف مع التاريخ
            $filename = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            $fullPath = storage_path('app/' . $this->backupPath . '/' . $filename);

            // التأكد من وجود المجلد
            if (!Storage::exists($this->backupPath)) {
                Storage::makeDirectory($this->backupPath);
            }

            // إنشاء أمر النسخ الاحتياطي
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $fullPath
            );

            // تنفيذ الأمر
            $process = Process::fromShellCommandline($command);
            $process->run();

            if ($process->isSuccessful()) {
                // ضغط الملف
                $zipCommand = sprintf('gzip %s', $fullPath);
                $zipProcess = Process::fromShellCommandline($zipCommand);
                $zipProcess->run();

                // رفع النسخة الاحتياطية إلى التخزين السحابي إذا تم تحديد ذلك
                if ($this->option('store') === 'cloud') {
                    $this->uploadToCloud($filename . '.gz');
                }

                // حذف النسخ القديمة
                $this->cleanOldBackups();

                $this->info('تم إنشاء النسخة الاحتياطية بنجاح!');
                Log::info('تم إنشاء نسخة احتياطية جديدة: ' . $filename);
            } else {
                throw new \Exception('فشل في إنشاء النسخة الاحتياطية');
            }
        } catch (\Exception $e) {
            $this->error('حدث خطأ: ' . $e->getMessage());
            Log::error('فشل في إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    private function uploadToCloud($filename)
    {
        try {
            $localPath = storage_path('app/' . $this->backupPath . '/' . $filename);
            $cloudPath = $this->backupPath . '/' . $filename;

            // رفع الملف إلى التخزين السحابي
            Storage::cloud()->put($cloudPath, file_get_contents($localPath));

            $this->info('تم رفع النسخة الاحتياطية إلى التخزين السحابي');
        } catch (\Exception $e) {
            $this->error('فشل في رفع النسخة الاحتياطية إلى التخزين السحابي: ' . $e->getMessage());
            Log::error('فشل في رفع النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    private function cleanOldBackups()
    {
        $files = Storage::files($this->backupPath);
        $sorted = collect($files)->sort()->values();

        if ($sorted->count() > $this->keepBackups) {
            $filesToDelete = $sorted->slice(0, $sorted->count() - $this->keepBackups);
            
            foreach ($filesToDelete as $file) {
                Storage::delete($file);
                Log::info('تم حذف نسخة احتياطية قديمة: ' . $file);
            }
        }
    }
}
