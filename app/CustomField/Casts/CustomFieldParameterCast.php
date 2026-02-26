<?php

namespace App\CustomField\Casts;

use App\CustomField\Enums\FieldParameter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CustomFieldParameterCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        $parameters = json_decode($value, true);

        return array_map(fn($item) => FieldParameter::from($item), $parameters ?? []);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $data = array_map(fn($item) => $item instanceof FieldParameter ? $item->value : $item, $value);

        return [
            $key => json_encode($data),
        ];
    }
}
