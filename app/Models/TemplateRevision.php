<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateRevision extends Model
{
    protected $fillable = [
        'template_id',
        'user_id',
        'layout',
        'styles',
        'components',
        'version',
        'comment',
        'is_major'
    ];

    protected $casts = [
        'layout' => 'array',
        'styles' => 'array',
        'components' => 'array',
        'version' => 'float',
        'is_major' => 'boolean'
    ];

    public function template()
    {
        return $this->belongsTo(PageTemplate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restore()
    {
        $template = $this->template;
        
        if ($this->layout) {
            $template->layout = $this->layout;
        }
        
        if ($this->styles) {
            $template->styles = $this->styles;
        }
        
        if ($this->components) {
            $template->components = $this->components;
        }
        
        $template->version = $this->version;
        $template->save();
    }
}
