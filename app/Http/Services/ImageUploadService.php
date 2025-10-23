<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function uploadAndReplace($model, $file, string $folder, string $attribute = 'image')
    {
        if (!$file) {
            return false;
        }

        // Delete old image if exists
        if ($model->{$attribute}) {
            Storage::disk()->delete($model->{$attribute});
        }

        // Store new image
        $model->{$attribute} = $file->store($folder, config('filesystems.default'));
        $model->save();

        return true;
    }
}
