<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityCustomization extends Model
{
    protected $fillable = [
        'facility_id',
        'template_id',
        'layout',
        'styles',
        'components_data',
        'settings',
        'is_draft',
        'published_at'
    ];

    protected $casts = [
        'layout' => 'array',
        'styles' => 'array',
        'components_data' => 'array',
        'settings' => 'array',
        'is_draft' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function template()
    {
        return $this->belongsTo(PageTemplate::class, 'template_id');
    }

    public function revisions()
    {
        return $this->hasMany(CustomizationRevision::class);
    }

    public function createRevision($comment = null)
    {
        return $this->revisions()->create([
            'user_id' => auth()->id(),
            'layout' => $this->layout,
            'styles' => $this->styles,
            'components_data' => $this->components_data,
            'settings' => $this->settings,
            'version' => $this->getNextVersion(),
            'comment' => $comment
        ]);
    }

    protected function getNextVersion()
    {
        $lastRevision = $this->revisions()->latest()->first();
        if (!$lastRevision) {
            return '1.0.0';
        }

        $parts = explode('.', $lastRevision->version);
        $parts[2] = (int)$parts[2] + 1;
        return implode('.', $parts);
    }

    public function publish()
    {
        $this->is_draft = false;
        $this->published_at = now();
        $this->save();

        $this->createRevision('Published changes');
    }

    public function saveDraft()
    {
        $this->is_draft = true;
        $this->save();
    }

    public function revertToRevision($revisionId)
    {
        $revision = $this->revisions()->findOrFail($revisionId);
        
        $this->layout = $revision->layout;
        $this->styles = $revision->styles;
        $this->components_data = $revision->components_data;
        $this->settings = $revision->settings;
        $this->is_draft = true;
        $this->save();

        return $this->createRevision('Reverted to version ' . $revision->version);
    }
}
