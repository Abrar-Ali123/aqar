<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('الأسئلة الشائعة')</h2>
    <div class="accordion" id="faqAccordion">
        @forelse($faq as $i => $item)
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading{{ $i }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $i }}" aria-expanded="false" aria-controls="faqCollapse{{ $i }}">
                        {{ $item['question'] }}
                    </button>
                </h2>
                <div id="faqCollapse{{ $i }}" class="accordion-collapse collapse" aria-labelledby="faqHeading{{ $i }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        {{ $item['answer'] }}
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">@lang('لا توجد أسئلة شائعة بعد.')</div>
        @endforelse
    </div>
</section>
