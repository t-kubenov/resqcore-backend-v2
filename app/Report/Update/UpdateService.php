<?php

namespace App\Report\Update;

use App\CustomField\CustomFieldValueService;
use App\Document\GeneralService;
use App\Report\Report;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class UpdateService extends GeneralService
{
    public function createUpdate($report, $attributes)
    {
        $user = Auth::user();

        $attributes['reported_by'] = $user->id;

        $update = $report->updates()->create($attributes);

        CustomFieldValueService::store($user->rescue_company_id, $update, $attributes);
        $update->apply();

        if (isset($attributes['transport'])) {
            $update->parseTransport($attributes['transport']);
            $update->load('transport');
        }

        return $update;
    }

    public function updateRequest(StoreUpdateRequest $request, $update, $attributes): Update
    {
        if (config('app.debug')) {
            return $this->createUpdate($update, $attributes);
        }

        $key = 'update-create:' . (Auth::user()?->id ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException(__('throttle.report-create'));
        }

        $update = $this->createUpdate($update, $attributes);

        RateLimiter::hit($key, 60); // 60 seconds cooldown

        return $update;
    }

    public function getUpdateLog(Report $report): Collection
    {
        $report->load([
            'updates.transport',
            'updates.reporter',
            'transport',
            'reporter',
        ]);

        $customValues = $report->customFieldValueArray();

        // Transform the Report into an Update format
        $reportAsUpdate = new Update();
        $reportAsUpdate->fill($report->toArray());

        $reportAsUpdate = $this->loadCustomValues($reportAsUpdate, $customValues);

        // Add missing fields to match the Update structure
        $reportAsUpdate->id = $report->id; // Add Report ID
        $reportAsUpdate->created_at = $report->created_at;
        $reportAsUpdate->updated_at = $report->updated_at;

        // Add relationships (transport and reporter)
        $reportAsUpdate->setRelation('transport', $report->transport);
        $reportAsUpdate->setRelation('reporter', $report->reporter);
        $reportAsUpdate->setRelation('customFieldValues', $report->customFieldValuesUsedInUpdates());
//        dd($report->customFieldValuesUsedInUpdates());

        // Merge the transformed report and its updates into a single array
        $updatesArray = array_merge(
            [$reportAsUpdate],
            $report->updates->map(function ($update) {
                // Ensure each update retains its transport relationship
                $update->load('transport');

                return $this->loadCustomValues($update, $update->customFieldValueArray());

            })->all()
        );


        // Return the combined result as a Laravel collection
        return new Collection($updatesArray);
    }

    private function loadCustomValues(Update $update, array $customValues = []): Update
    {
        foreach ($customValues as $key => $value) {
            $update->{$key} = $value;
        }

        return $update;
    }
}
