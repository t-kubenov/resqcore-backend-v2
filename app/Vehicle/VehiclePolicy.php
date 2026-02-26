<?php

namespace App\Vehicle;

use App\Tenant\User;
use Illuminate\Auth\Access\Response;

final class VehiclePolicy
{
    private function matchScope(User $user, $model): bool
    {
        return match($user->role->name) {
            'admin' => true,
            'central_office_user' => $model->branch_office->rescue_company_id === $user->rescue_company_id,
            'branch_office_user' => $model->branch_office_id === $user->branch_id,
            default => false,
        };
    }

    public function viewAny(User $user): Response
    {
        if ($user->cant('view vehicle')) {
            return Response::deny(__('documents.policy.access-denied'));
        }

        return Response::allow();
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        if ($user->cant('view vehicle')){
            return false;
        }

        return $this->matchScope($user, $vehicle);
    }

    public function create(User $user): bool
    {
        return $user->can('create vehicle');
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        if ($user->cant('update vehicle')){
            return false;
        }

        return $this->matchScope($user, $vehicle);
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        if ($user->cant('delete vehicle')){
            return false;
        }

        return $this->matchScope($user, $vehicle);
    }
}
