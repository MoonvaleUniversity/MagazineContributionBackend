<?php

namespace App\Models\Traits\Audit;

use Illuminate\Support\Facades\Auth;

trait Audit
{
    public static function bootAudit()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id() ?? null;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? null;
        });
    }
}
