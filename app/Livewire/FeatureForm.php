<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Feature;

class FeatureForm extends Component
{
    use WithFileUploads;

    public $features;
    public $selectedFeatureId;
    public $names = [];
    public $icon;
    public $newNames = [];
    public $newIcon;

    protected $rules = [
        'names' => 'required|array',
        'names.*' => 'required|string',
        'icon' => 'nullable|image|max:1024',
        'newNames' => 'required|array',
        'newNames.*' => 'required|string',
        'newIcon' => 'required|image|max:1024',
    ];

    public function mount()
    {
        $this->features = Feature::with('translations')->get();
    }

    public function selectFeature($featureId)
    {
        $this->selectedFeatureId = $featureId;
        $this->names = Feature::find($featureId)->translations->pluck('name', 'locale')->toArray();
        $this->resetErrorBag();
    }

    public function updateFeature()
    {

        $feature = Feature::find($this->selectedFeatureId);
        foreach ($this->names as $locale => $name) {
             $feature->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $name]
            );
        }

        if ($this->icon) {
            $iconPath = $this->icon->store('features', 'public');
            $feature->update(['icon' => $iconPath]);
        }

        $this->features = Feature::with('translations')->get();
        $this->selectedFeatureId = null;
        $this->reset(['icon', 'names']);
    }

    public function addFeature()
    {

        $iconPath = $this->newIcon->store('features', 'public');

        $feature = Feature::create(['icon' => $iconPath]);
        foreach ($this->newNames as $locale => $name) {
            $feature->translations()->create([
                'locale' => $locale,
                'name' => $name,
            ]);
        }

        $this->features = Feature::with('translations')->get();
        $this->reset(['newIcon', 'newNames']);
    }

    public function deleteFeature($featureId)
    {
        $feature = Feature::find($featureId);
        if ($feature) {
            $feature->translations()->delete();
            $feature->delete();
        }

        $this->features = Feature::with('translations')->get();
    }

    public function render()
    {
        return view('livewire.feature-form');
    }
}
