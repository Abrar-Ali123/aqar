<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ComponentCustomizer extends Component
{
    public $facility;
    public $component;
    public $settings;
    public $isOpen = false;

    protected $listeners = ['openCustomizer', 'saveSettings'];

    public function mount($facility)
    {
        $this->facility = $facility;
        $this->settings = $facility->component_settings ?? [];
    }

    public function openCustomizer($componentId)
    {
        $this->component = $componentId;
        $this->settings = $this->facility->getComponentSettings($componentId);
        $this->isOpen = true;
    }

    public function saveSettings()
    {
        $this->facility->updateComponentSettings($this->component, $this->settings);
        $this->isOpen = false;
        $this->emit('settingsUpdated');
    }

    public function render()
    {
        return view('livewire.component-customizer');
    }
}
