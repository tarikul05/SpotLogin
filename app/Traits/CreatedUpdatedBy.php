<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;

trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy()
    {
        if(Auth::check()) {
            // updating created_by and updated_by when model is created
            static::creating(function ($model) {
                if (!$model->isDirty('created_by')) {
                    $model->created_by = Auth::user()->id;
                }
            });

        

            // updating updated_by when model is updated
            static::updating(function ($model) {
                if (!$model->isDirty('modified_by')) {
                    $model->modified_by = Auth::user()->id;
                }
            });
        }
    }
}
