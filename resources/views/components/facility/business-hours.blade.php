@props(['data', 'facility'])

<div class="business-hours">
    @if(isset($data['title']))
        <h2 class="text-2xl font-bold mb-4">{{ $data['title'] }}</h2>
    @endif

    <div class="space-y-2">
        @php
            $days = [
                'sunday' => 'الأحد',
                'monday' => 'الاثنين',
                'tuesday' => 'الثلاثاء',
                'wednesday' => 'الأربعاء',
                'thursday' => 'الخميس',
                'friday' => 'الجمعة',
                'saturday' => 'السبت'
            ];

            $currentDay = strtolower(date('l'));
            $now = \Carbon\Carbon::now();
        @endphp

        @foreach($days as $dayKey => $dayName)
            @php
                $hours = $facility->opening_hours[$dayKey] ?? null;
                $isOpen = false;
                
                        @endif
                    @else
                        <span class="text-red-600">مغلق</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
