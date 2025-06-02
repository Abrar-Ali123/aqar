<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Facility;
use App\Models\FacilityPage;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\PageTemplate;
use Illuminate\Support\Str;

class FacilityPagesManager extends Component
{
    public $facility;
    public $pages;
    public $pageTitle;
    public $pageSlug;
    public $pageOrder = 0;
    public $editingPageId = null;
    public $attributesList = [];
    public $attributeValues = [];
    public $templates = [];
    public $selectedTemplateId = null;
    public $designSettings = [];
    public $enableContactForm = false;
    public $enableReviews = false;
    public $scheduledFrom;
    public $scheduledTo;
    public $pageHistories = [];
    public $metaTitle;
    public $metaDescription;
    public $metaImage;
    public $analyticsCode;
    public $facebookPixel;

    protected $rules = [
        'pageTitle' => 'required|string|max:255',
        'pageSlug' => 'required|string|max:255|alpha_dash',
        'pageOrder' => 'nullable|integer',
        'metaTitle' => 'nullable|string|max:255',
        'metaDescription' => 'nullable|string|max:255',
        'metaImage' => 'nullable|string|max:255',
        'analyticsCode' => 'nullable|string',
        'facebookPixel' => 'nullable|string',
    ];

    public function mount($facilityId)
    {
        $this->facility = Facility::findOrFail($facilityId);
        $this->pages = $this->facility->pages()->orderBy('order')->get();
        $this->templates = PageTemplate::all();
        $this->designSettings = [
            'primary_color' => '#2196f3',
            'secondary_color' => '#f44336',
            'font' => 'Tajawal',
            'custom_css' => '',
        ];
        $this->enableContactForm = false;
        $this->enableReviews = false;
        $this->scheduledFrom = null;
        $this->scheduledTo = null;
        $this->pageHistories = [];
        $this->metaTitle = null;
        $this->metaDescription = null;
        $this->metaImage = null;
        $this->analyticsCode = null;
        $this->facebookPixel = null;
    }

    public function render()
    {
        return view('livewire.facility-pages-manager');
    }

    public function createPage()
    {
        $this->validate();
        $page = FacilityPage::create([
            'facility_id' => $this->facility->id,
            'template_id' => $this->selectedTemplateId,
            'title' => $this->pageTitle,
            'slug' => Str::slug($this->pageSlug),
            'order' => $this->pageOrder,
            'is_active' => true,
            'design_settings' => $this->designSettings,
            'enable_contact_form' => $this->enableContactForm,
            'enable_reviews' => $this->enableReviews,
            'scheduled_from' => $this->scheduledFrom,
            'scheduled_to' => $this->scheduledTo,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_image' => $this->metaImage,
            'analytics_code' => $this->analyticsCode,
            'facebook_pixel' => $this->facebookPixel,
        ]);
        $this->pages->push($page);
        $this->reset(['pageTitle','pageSlug','pageOrder','selectedTemplateId','designSettings','enableContactForm','enableReviews','scheduledFrom','scheduledTo','pageHistories','metaTitle','metaDescription','metaImage','analyticsCode','facebookPixel']);
        session()->flash('success', __('Page created successfully.'));
    }

    public function updatedSelectedTemplateId($value)
    {
        $template = PageTemplate::find($value);
        if ($template) {
            $this->attributeValues = [];
            foreach ($template->default_attributes as $attr) {
                $this->attributeValues[$attr['key']] = ['value' => null];
            }
        }
    }

    public function editPage($pageId)
    {
        $page = FacilityPage::findOrFail($pageId);
        $this->editingPageId = $page->id;
        $this->pageTitle = $page->title;
        $this->pageSlug = $page->slug;
        $this->pageOrder = $page->order;
        $this->selectedTemplateId = $page->template_id;
        $this->designSettings = $page->design_settings ?? [];
        $this->enableContactForm = $page->enable_contact_form;
        $this->enableReviews = $page->enable_reviews;
        $this->scheduledFrom = $page->scheduled_from;
        $this->scheduledTo = $page->scheduled_to;
        $this->pageHistories = $page->histories()->with('user')->latest()->limit(10)->get();
        $this->metaTitle = $page->meta_title;
        $this->metaDescription = $page->meta_description;
        $this->metaImage = $page->meta_image;
        $this->analyticsCode = $page->analytics_code;
        $this->facebookPixel = $page->facebook_pixel;
        // تحميل الخصائص المرتبطة
        $this->attributeValues = $page->attributeValues()->with('attribute')->get()->keyBy('attribute_id')->toArray();
        $this->attributesList = Attribute::all();
    }

    public function updatePage()
    {
        $this->validate();
        $page = FacilityPage::findOrFail($this->editingPageId);
        $page->update([
            'title' => $this->pageTitle,
            'slug' => Str::slug($this->pageSlug),
            'order' => $this->pageOrder,
            'design_settings' => $this->designSettings,
            'enable_contact_form' => $this->enableContactForm,
            'enable_reviews' => $this->enableReviews,
            'scheduled_from' => $this->scheduledFrom,
            'scheduled_to' => $this->scheduledTo,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_image' => $this->metaImage,
            'analytics_code' => $this->analyticsCode,
            'facebook_pixel' => $this->facebookPixel,
        ]);
        // سجل التعديل
        $page->histories()->create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'snapshot' => $page->toArray(),
        ]);
        // تحديث القيم للخصائص
        foreach ($this->attributesList as $attribute) {
            $value = $this->attributeValues[$attribute->id]['value'] ?? null;
            if ($value !== null) {
                AttributeValue::updateOrCreate([
                    'attribute_id' => $attribute->id,
                    'attributeable_id' => $page->id,
                    'attributeable_type' => FacilityPage::class,
                ], [
                    'value' => $value,
                ]);
            }
        }
        $this->editingPageId = null;
        $this->reset(['pageTitle','pageSlug','pageOrder','attributeValues','attributesList','selectedTemplateId','designSettings','enableContactForm','enableReviews','scheduledFrom','scheduledTo','pageHistories','metaTitle','metaDescription','metaImage','analyticsCode','facebookPixel']);
        session()->flash('success', __('Page updated successfully.'));
    }

    public function cancelEdit()
    {
        $this->editingPageId = null;
        $this->reset(['pageTitle','pageSlug','pageOrder','attributeValues','attributesList','selectedTemplateId','designSettings','enableContactForm','enableReviews','scheduledFrom','scheduledTo','pageHistories','metaTitle','metaDescription','metaImage','analyticsCode','facebookPixel']);
    }
}
