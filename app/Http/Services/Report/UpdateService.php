<?php

namespace App\Http\Services\Report;

use App\Http\Requests\Report\StoreUpdateRequest;
use App\Http\Services\CustomField\CustomFieldValueService;
use App\Http\Services\GeneralService;
use App\Models\Report\Update;
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
        $update->close();
        $update->calculateTotals();
        $update->updateReportTimestamp();

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
}
