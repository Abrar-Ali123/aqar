<?php

namespace App\Console\Commands;

use App\Models\SavedSearch;
use App\Models\Product;
use App\Notifications\NewSearchResults;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendSearchNotifications extends Command
{
    protected $signature = 'search:notify';
    protected $description = 'Send notifications for saved searches with new results';

    public function handle()
    {
        $frequencies = [
            'daily' => Carbon::now()->subDay(),
            'weekly' => Carbon::now()->subWeek(),
            'monthly' => Carbon::now()->subMonth(),
        ];

        foreach ($frequencies as $frequency => $since) {
            $searches = SavedSearch::where('notify', true)
                ->where('frequency', $frequency)
                ->where(function ($query) use ($since) {
                    $query->where('last_notification_at', '<=', $since)
                          ->orWhereNull('last_notification_at');
                })
                ->get();

            foreach ($searches as $search) {
                $filters = json_decode($search->filters, true);
                
                $query = Product::query();
                
                if (!empty($filters['q'])) {
                    $query->where(function($q) use ($filters) {
                        $q->where('title', 'like', "%{$filters['q']}%")
                          ->orWhere('description', 'like', "%{$filters['q']}%");
                    });
                }
                
                if (!empty($filters['category'])) {
                    $query->where('category_id', $filters['category']);
                }
                
                if (!empty($filters['price_min'])) {
                    $query->where('price', '>=', $filters['price_min']);
                }
                
                if (!empty($filters['price_max'])) {
                    $query->where('price', '<=', $filters['price_max']);
                }
                
                if (!empty($filters['features'])) {
                    foreach ($filters['features'] as $featureId => $value) {
                        $query->whereHas('features', function($q) use ($featureId, $value) {
                            $q->where('feature_id', $featureId)
                              ->where('value', $value);
                        });
                    }
                }
                
                $newResults = $query->where('created_at', '>=', $since)->get();
                
                if ($newResults->isNotEmpty()) {
                    $search->user->notify(new NewSearchResults($search, $newResults));
                }
                
                $search->update(['last_notification_at' => now()]);
            }
        }
    }
}
