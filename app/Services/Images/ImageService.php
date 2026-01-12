<?php

namespace App\Services\Images;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Storage;

class ImageService
{
    private string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    public function persist(Model $model, UploadedFile $file, string $directory): void
    {
        $path = $file->store($directory, $this->disk);

        if (! $path) {
            throw new RuntimeException(__('documents.image.upload_failure'));
        }

        $model->images()->create([
            'storage' => $this->disk,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);
    }

    public function delete(Image $image)
    {
        $storage = Storage::disk($this->disk);

        $storage->delete($image->file_path);

        $image->delete();
    }
}
