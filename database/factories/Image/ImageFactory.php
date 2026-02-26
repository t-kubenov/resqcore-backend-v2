<?php

namespace Database\Factories\Image;

use App\Image\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'storage' => 'public',
            'file_path' => 'images/' . $this->faker->uuid . '.jpg',
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(10_000, 5_000_000),
        ];
    }

    public function forModel(Model $model): static
    {
        return $this->state(fn () => [
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
        ]);
    }
}
