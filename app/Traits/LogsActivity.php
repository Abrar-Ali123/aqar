<?php

namespace App\Traits;

use App\Events\UniversalEntityEvent;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->dispatchUniversalEvent(
                static::class,
                $model->id,
                'created',
                Auth::id(),
                $model->toArray()
            );
        });
        static::updated(function ($model) {
            $model->dispatchUniversalEvent(
                static::class,
                $model->id,
                'updated',
                Auth::id(),
                $model->getChanges()
            );
        });
        static::deleted(function ($model) {
            $model->dispatchUniversalEvent(
                static::class,
                $model->id,
                'deleted',
                Auth::id(),
                $model->toArray()
            );
        });
    }
}
