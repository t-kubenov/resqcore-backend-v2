<?php

namespace App\FieldOption\Handlers;

use App\Vehicle\Enums\ActionType;
use App\Vehicle\Enums\DriveType;
use App\Vehicle\Enums\FuelType;
use App\Vehicle\Enums\GearboxType;
use App\Vehicle\Enums\TechnicalCondition;
use App\Vehicle\Enums\UsageType;
use App\FieldOption\Contracts\FieldOptionHandler;
use App\FieldOption\Support\FieldOptionContext;
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
