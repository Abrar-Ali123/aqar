<?php

namespace App\Console\Commands;

use App\Models\TemporaryPermission;
use Illuminate\Console\Command;

class CleanupTemporaryPermissions extends Command
{
    protected $signature = 'permissions:cleanup';
    protected $description = 'تنظيف الصلاحيات المؤقتة المنتهية';

    public function handle()
    {
        $this->info('بدء تنظيف الصلاحيات المؤقتة المنتهية...');

        $count = TemporaryPermission::cleanup();

        $this->info("تم تنظيف {$count} صلاحية مؤقتة منتهية.");
    }
}
