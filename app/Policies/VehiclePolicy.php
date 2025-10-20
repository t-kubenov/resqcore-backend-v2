<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle\Vehicle;

class VehiclePolicy extends GeneralPolicy
{
    public function update(User $user, Vehicle $vehicle): bool
    {
        if ($user->isAdmin()){
            return true;
        }

        if ($user->isCentralOffice()){
            return $user->rescue_company()->id === $vehicle->rescue_company()->id;
        }

        return false;
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        if ($user->isAdmin()){
            return true;
        }

        if ($user->isCentralOffice()){
            return $user->rescue_company()->id === $vehicle->rescue_company()->id;
        }

        return false;
    }
}
