<?php

namespace App\Http\Services\Vehicle;

use App\Models\Vehicle\Vehicle;
use Carbon\Carbon;

class VehicleAlertService
{
    public function getAlerts(Vehicle $vehicle): ?array
    {
        $warnings = [];

        $expirationFields = [
            'insurance_expiration_date' => __('vehicles.fields.insurance_expiration_date'),
            'inspection_expiration_date' => __('vehicles.fields.inspection_expiration_date'),
        ];

        foreach ($expirationFields as $field => $label) {
            $date = $vehicle->$field;

            if (!$date) {
                continue;
            }

            $warning = $this->generateExpirationWarning($date, $label);

            if ($warning) {
                $warnings[] = $warning;
            }
        }

        return [
            ...($warnings? ['warning' => $warnings]: [])
        ];
    }

    protected function generateExpirationWarning(Carbon|string $date, string $label): ?string
    {
        $now = now();
        $date = Carbon::parse($date);
        $daysUntilExpiration = $now->diffInDays($date);

        if ($daysUntilExpiration < 0) {
            return __('vehicles.alerts.expiration.expired', ['field' => $label]);
        }

        if ($daysUntilExpiration <= 30) {
            $days = (int) $daysUntilExpiration;
            return trans_choice('vehicles.alerts.expiration.expires_in', $days, [
                'field' => $label
            ]);
        }

        return null;
    }
}
