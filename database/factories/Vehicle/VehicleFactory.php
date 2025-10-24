<?php

namespace Database\Factories\Vehicle;

use App\Enums\Vehicle\ActionType;
use App\Enums\Vehicle\DriveType;
use App\Enums\Vehicle\FuelType;
use App\Enums\Vehicle\GearboxType;
use App\Enums\Vehicle\TechnicalCondition;
use App\Enums\Vehicle\UsageType;
use App\Models\BranchOffice;
use App\Models\Vehicle\VehicleCompany;
use App\Models\Vehicle\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_office_id' => BranchOffice::factory(),
            'brand' => ucwords($this->faker->word() . ' ' . $this->faker->word()),
            'vehicle_type_id' => VehicleType::factory(),
            'plate_number' => strtoupper($this->faker->bothify('?###???')), // Example: A123CDB
            'year' => $this->faker->numberBetween(1990, now()->year),

            'engine_size' => $this->faker->randomFloat(min: 0.5, max: 4.0),
            'fuel_type' => $this->faker->randomElement(FuelType::cases()),
            'gearbox' => $this->faker->randomElement(GearboxType::cases()),
            'drive' => $this->faker->randomElement(DriveType::cases()),

            'location' => $this->faker->address(),
            'vehicle_owner_id' => VehicleCompany::factory(),
            'usage_type' => $this->faker->randomElement(UsageType::cases()),
            'vehicle_user_id' => VehicleCompany::factory(),
            'rental_contract' => $this->faker->optional()->bothify('RC-####-??'),
            'rental_price' => $this->faker->optional()->randomFloat(2, 10000, 50000),
            'technical_condition' => $this->faker->randomElement(TechnicalCondition::cases()),
            'action' => $this->faker->randomElement(ActionType::cases()),
            'notes' => $this->faker->optional()->sentence(),
            'insurance_expiration_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'inspection_expiration_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'inspection_date' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
