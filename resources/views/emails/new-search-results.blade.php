@component('mail::message')
# {{ __('notifications.new_search_results_subject', ['count' => $count]) }}

{{ __('notifications.new_search_results_intro', ['count' => $count, 'name' => $searchName]) }}

@foreach($results as $result)
* **{{ $result->title }}**
  {{ __('pages.price') }}: {{ number_format($result->price) }} {{ __('pages.currency') }}
@endforeach

@if($count > 5)
{{ __('notifications.and_more_results', ['count' => $count - 5]) }}
@endif

@component('mail::button', ['url' => $url])
{{ __('notifications.view_all_results') }}
@endcomponent

{{ __('notifications.email_footer') }}
@endcomponent
