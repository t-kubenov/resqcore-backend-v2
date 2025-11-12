<?php

namespace App\Services\FieldOptions\Handlers;

use App\Enums\Vehicle\ActionType;
use App\Enums\Vehicle\DriveType;
use App\Enums\Vehicle\FuelType;
use App\Enums\Vehicle\GearboxType;
use App\Enums\Vehicle\TechnicalCondition;
use App\Enums\Vehicle\UsageType;
use App\Services\FieldOptions\Contracts\FieldOptionHandler;
use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class EnumFieldHandler implements FieldOptionHandler
{
    private array $enumFieldName = [
        'action_type' => ActionType::class,
        'fuel_type' => FuelType::class,
        'gearbox' => GearboxType::class,
        'drive' => DriveType::class,
        'usage_type' => UsageType::class,
        'technical_condition' => TechnicalCondition::class,
    ];

    public function supports(string $fieldName): bool
    {
        return in_array($fieldName, array_keys($this->enumFieldName));
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null, ?string $fieldName = null): LengthAwarePaginator
    {
        $cases = $this->enumFieldName[$fieldName]::cases();

        $options = collect($cases)->map(fn($case) => [
            'id' => $case->value, // or $case->name for pure enums
            'name' => __('etc.enum_options.' . $case->name), // or a custom label method
        ]);

        if ($search) {
            $options = $options->filter(fn($option) =>
                stripos($option['name'], $search) !== false
            );
        }

        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage();
        $currentItems = $options->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new Paginator(
            $currentItems,
            $options->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }
}
