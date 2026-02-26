<?php

namespace App\Image;

use App\Vehicle\Vehicle;
use Illuminate\Validation\ValidationException;

class ImageableResolver
{
    public function resolve(string $document): string
    {
        return match ($document) {
            'vehicles' => Vehicle::class,
            default => throw ValidationException::withMessages(['document' => __('documents.image.model_not_found')]),
        };
    }
}
