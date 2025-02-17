<?php

namespace App\Models\Traits\Audit;

trait Audit
{
    public static function bootAudit()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id() ?? null;  
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id() ?? null;
        });
    }
}
