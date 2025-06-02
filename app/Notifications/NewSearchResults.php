<?php

namespace App\Notifications;

use App\Models\SavedSearch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class NewSearchResults extends Notification implements ShouldQueue
{
    use Queueable;

    protected $savedSearch;
    protected $newResults;

    public function __construct(SavedSearch $savedSearch, Collection $newResults)
    {
        $this->savedSearch = $savedSearch;
        $this->newResults = $newResults;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = $this->savedSearch->url;
        $count = $this->newResults->count();

        return (new MailMessage)
            ->subject(__('notifications.new_search_results_subject', ['count' => $count]))
            ->line(__('notifications.new_search_results_intro', [
                'count' => $count,
                'name' => $this->savedSearch->name
            ]))
            ->line(__('notifications.new_search_results_preview'))
            ->view('emails.new-search-results', [
                'results' => $this->newResults->take(5),
                'count' => $count,
                'searchName' => $this->savedSearch->name,
                'url' => $url
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'saved_search_id' => $this->savedSearch->id,
            'saved_search_name' => $this->savedSearch->name,
            'new_results_count' => $this->newResults->count(),
            'url' => $this->savedSearch->url
        ];
    }
}
