<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'storage',
        'file_path',
        'mime_type',
        'size'
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo('model');
    }

    public function getUrlAttribute(): string
    {
        return \Storage::disk(config('filesystems.default'))->url($this->file_path);
    }
}
