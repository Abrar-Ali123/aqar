<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Facility;

class ListFacilities extends Command
{
    protected $signature = 'facilities:list';
    protected $description = 'List all facilities';

    public function handle()
    {
        $facilities = Facility::with('translations')->get();
        
        $this->table(
            ['ID', 'Name (AR)', 'Name (EN)', 'Rating', 'Reviews', 'Status'],
            $facilities->map(function ($facility) {
                return [
                    $facility->id,
                    $facility->translate('ar')->name ?? '-',
                    $facility->translate('en')->name ?? '-',
                    $facility->rating ?? '-',
                    $facility->reviews_count ?? '0',
                    $facility->is_verified ? 'Verified' : 'Not Verified'
                ];
            })
        );
    }
}
