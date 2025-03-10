<?php

namespace App\Models\Traits;

trait Version
{
    public static function bootVersion()
    {
        static::creating(function ($model) {
            $model->version = 1;
        });

        static::updating(function ($model) {
            $model->version++;
        });
    }
}
