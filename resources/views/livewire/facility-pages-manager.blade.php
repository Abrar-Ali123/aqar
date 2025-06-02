<div>
    <h2 class="mb-3">@lang('إدارة صفحات المنشأة')</h2>
    <livewire:facility-pages-statistics :facility-id="$facility->id" />
    <livewire:facility-pages-visits-chart :facility-id="$facility->id" />
    <div class="mb-4">
        <form wire:submit.prevent="{{ $editingPageId ? 'updatePage' : 'createPage' }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">@lang('القالب')</label>
                    <select class="form-select" wire:model="selectedTemplateId">
                        <option value="">@lang('اختر قالب')</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('عنوان الصفحة')</label>
                    <input type="text" class="form-control" wire:model.defer="pageTitle">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('الرابط (slug)')</label>
                    <input type="text" class="form-control" wire:model.defer="pageSlug">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('الترتيب')</label>
                    <input type="number" class="form-control" wire:model.defer="pageOrder">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        {{ $editingPageId ? __('تحديث الصفحة') : __('إضافة صفحة') }}
                    </button>
                    @if($editingPageId)
                        <button type="button" class="btn btn-secondary mt-1 w-100" wire:click="cancelEdit">@lang('إلغاء')</button>
                    @endif
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">@lang('عنوان SEO')</label>
                    <input type="text" class="form-control" wire:model.defer="metaTitle">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('وصف SEO')</label>
                    <input type="text" class="form-control" wire:model.defer="metaDescription">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('صورة SEO')</label>
                    <input type="file" class="form-control" wire:model.defer="metaImage">
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">@lang('تفعيل نموذج التواصل')</label>
                    <input type="checkbox" class="form-check-input" wire:model.defer="enableContactForm">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('تفعيل التعليقات/الشهادات')</label>
                    <input type="checkbox" class="form-check-input" wire:model.defer="enableReviews">
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">@lang('تاريخ النشر من')</label>
                    <input type="datetime-local" class="form-control" wire:model.defer="scheduledFrom">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('تاريخ النشر إلى')</label>
                    <input type="datetime-local" class="form-control" wire:model.defer="scheduledTo">
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">@lang('كود Google Analytics')</label>
                    <input type="text" class="form-control" wire:model.defer="analyticsCode">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('Facebook Pixel')</label>
                    <input type="text" class="form-control" wire:model.defer="facebookPixel">
                </div>
            </div>
            @if($editingPageId && $attributesList)
                <hr>
                <h5 class="mt-3">@lang('خصائص الصفحة')</h5>
                <div class="row g-2">
                    @foreach($attributesList as $attribute)
                        <div class="col-md-4">
                            <label class="form-label">{{ $attribute->translations[app()->getLocale()]['name'] ?? $attribute->key }}</label>
                            <input type="text" class="form-control" wire:model.defer="attributeValues.{{ $attribute->id }}.value">
                        </div>
                    @endforeach
                </div>
            @endif
            @if($selectedTemplateId && !$editingPageId)
                <hr>
                <h5 class="mt-3">@lang('حقول القالب')</h5>
                <div class="row g-2">
                    @php
                        $template = $templates->where('id', $selectedTemplateId)->first();
                    @endphp
                    @if($template && $template->default_attributes)
                        @foreach($template->default_attributes as $attr)
                            <div class="col-md-4">
                                <label class="form-label">{{ $attr['label'] }}</label>
                                @if($attr['type'] === 'text')
                                    <input type="text" class="form-control" wire:model.defer="attributeValues.{{ $attr['key'] }}.value">
                                @elseif($attr['type'] === 'wysiwyg')
                                    <textarea class="form-control" rows="3" wire:model.defer="attributeValues.{{ $attr['key'] }}.value"></textarea>
                                @elseif($attr['type'] === 'image')
                                    <input type="file" class="form-control" wire:model.defer="attributeValues.{{ $attr['key'] }}.value">
                                @elseif($attr['type'] === 'gallery')
                                    <input type="file" class="form-control" multiple wire:model.defer="attributeValues.{{ $attr['key'] }}.value">
                                @elseif($attr['type'] === 'repeater')
                                    <div class="border rounded p-2 mb-2 bg-light">
                                        <span class="fw-bold">{{ $attr['label'] }}</span>
                                        {{-- هنا يمكن بناء واجهة تكرارية للأعضاء أو الخدمات --}}
                                    </div>
                                @else
                                    <input type="text" class="form-control" wire:model.defer="attributeValues.{{ $attr['key'] }}.value">
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif
            @include('livewire.partials.page-design-settings')
        </form>
    </div>
    @if($editingPageId)
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('إدارة الوسائط')</div>
            <div class="card-body">
                <livewire:facility-page-media-manager :page="$page" />
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('إدارة القوالب')</div>
            <div class="card-body">
                <livewire:facility-page-template-manager :page="$page" />
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('مركز الترجمة')</div>
            <div class="card-body">
                <livewire:facility-page-translation-center :page="$page" />
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('معاينة مباشرة')</div>
            <div class="card-body">
                <livewire:facility-page-live-preview :page="$page" />
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('إدارة النسخ والتاريخ')</div>
            <div class="card-body">
                <livewire:facility-page-version-manager :page="$page" />
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('ترتيب الأقسام')</div>
            <div class="card-body">
                <livewire:facility-page-section-order-manager :page="$page" />
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('ترجمة الأقسام')</div>
            <div class="card-body">
                @foreach(['gallery','team','services','faq','offers','announcements','partners','social','testimonials','blog'] as $section)
                    <livewire:facility-page-section-translation-manager :page="$page" :section="$section" :key="$section.'-trans'" />
                    <hr>
                @endforeach
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('تخصيص تصميم الأقسام')</div>
            <div class="card-body">
                @foreach(['gallery','team','services','faq','offers','announcements','partners','social','testimonials','blog'] as $section)
                    <livewire:facility-page-section-style-manager :page="$page" :section="$section" :key="$section.'-style'" />
                    <hr>
                @endforeach
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('جدولة ظهور الأقسام')</div>
            <div class="card-body">
                @foreach(['gallery','team','services','faq','offers','announcements','partners','social','testimonials','blog'] as $section)
                    <livewire:facility-page-section-schedule-manager :page="$page" :section="$section" :key="$section.'-sched'" />
                    <hr>
                @endforeach
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('إدارة محتوى الأقسام')</div>
            <div class="card-body">
                @foreach(['gallery','team','services','faq','offers','announcements','partners','social','testimonials','blog'] as $section)
                    <livewire:facility-page-section-content-manager :page="$page" :section="$section" :key="$section" />
                    <hr>
                @endforeach
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">@lang('إدارة الأقسام الظاهرة')</div>
            <div class="card-body">
                <livewire:facility-page-section-manager :page="$page" />
            </div>
        </div>
    @endif
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-bold">@lang('قائمة الصفحات')</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>@lang('العنوان')</th>
                        <th>@lang('الرابط')</th>
                        <th>@lang('ترتيب')</th>
                        <th>@lang('نشطة؟')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                        <tr>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->slug }}</td>
                            <td>{{ $page->order }}</td>
                            <td>{!! $page->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-danger">لا</span>' !!}</td>
                            <td>
                                <button class="btn btn-sm btn-info" wire:click="editPage({{ $page->id }})">@lang('تعديل')</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($editingPageId)
        <div class="card mt-4">
            <div class="card-header bg-light fw-bold">@lang('سجل التعديلات')</div>
            <div class="card-body p-2">
                @if($pageHistories && count($pageHistories))
                    <ul class="list-group list-group-flush">
                        @foreach($pageHistories as $history)
                            <li class="list-group-item small">
                                <span class="fw-bold">{{ $history->user->name ?? 'System' }}</span>
                                - {{ __($history->action) }}<br>
                                <span class="text-muted">{{ $history->created_at->format('Y-m-d H:i') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted">@lang('لا يوجد سجل تعديلات بعد.')</div>
                @endif
            </div>
        </div>
    @endif
    @if(session()->has('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
</div>
