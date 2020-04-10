<?php


namespace App\Models\Traits;


use Ramsey\Uuid\Uuid;

trait GenerateUuid
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4();
        });
    }
}
