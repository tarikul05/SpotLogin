<?php

namespace App\Traits;

trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by') && auth()->user()) {
                $model->created_by = auth()->user()->id;
            }
            if (!$model->isDirty('modified_by')) {
                $model->modified_by = auth()->user()->id;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('modified_by') && auth()->user()) {
                $model->modified_by = auth()->user()->id;
            }
        });
    }
}
