<?php

namespace App\Http\Services\V2;

use App\Http\Requests\V2\StoreReportRequest;
use App\Models\Report;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ReportService
{
    public function index(Request $request)
    {
        $query = Report::orderByRaw("is_closed, COALESCE(last_update_created_at, created_at) DESC");

        $query = $this->filterQuery($query, $request);

        return $query->with(['transport', 'updates.transport'])->paginate();
    }

    public function export(Request $request)
    {
        $query = Report::orderBy("call_date");

        $query = $this->filterQuery($query, $request);

        return $query->with(['transport', 'updates.transport'])->paginate();
    }

    public function show($report)
    {
        $report->load([
            'updates' => function ($query) {
                $query->with('transport')->orderBy('created_at');
            },
            'reporter:id,first_name,last_name,patronymic,email'
        ]);

        return $report;
    }

    public function store(StoreReportRequest $request): Report
    {
        $user = Auth::user();
        $attributes = $request['data']['attributes'];

        $attributes['reported_by'] = $user->id;
        $attributes['branch_office_id'] = $user->branch_id ?? $attributes['branch_office_id']; //todo: move this to separate function
        $attributes['report_code'] = $this->generateReportCode($attributes['branch_office_id']);
        $attributes['emergency_type'] = $attributes['emergency_type']['id'];

        $report = Report::create($attributes);

        CustomFieldValueService::store($user->rescue_company_id, $report, $attributes);
        $this->checkReportForClosing($report);
        $this->calculateTotals($report);

        if (isset($attributes['transport'])) {
            $this->parseTransport($report, $attributes['transport']);
            $report->load('transport');
        }

        return $report;
    }

    public function destroy($id)
    {
        $report = Report::find($id);
        if ($report) {
            $report->delete();
            return true;
        }
        return false;
    }



    public function filterQuery($query, $request)
    {
        $query = $this->filterRescueCompany($query);
        $query = $this->filterBranchOffice($query);

        foreach ($request as $option => $value){
            if (array_key_exists($option, Report::filters())){
                $query->where($option, $value);
            }
        }

        if (isset($request['search'])) {
            $search = $request['search'];
            $query->where(function ($q) use ($search) {
                $q->where('line_facility_owner', 'ILIKE', "%{$search}%")
                    ->orWhere('emergency_location', 'ILIKE', "%{$search}%")
                    ->orWhereHas('facility_owner', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    private function filterRescueCompany($query)
    {
        $user = Auth::user();

        if ($user->access_level === 'app') {
            return $query;
        }

        return $query->whereHas('branch_office', function ($q) use ($user) {
            $q->where('rescue_company_id', $user->rescue_company->id);
        });
    }

    private function filterBranchOffice($query)
    {
        $user = Auth::user();

        if ($user->hasRole('branch_office_user')) {
            $query->where('branch_office_id', $user->branch_id);
        }
        return $query;
    }

    private function generateReportCode($branchOfficeId) // to mutator
    {
        $reportCount = Report::where('branch_office_id', $branchOfficeId)->count() + 1;
        return $branchOfficeId . '-' . $reportCount;
    }

    private function parseTransport(Report $report, array $transport)
    {
        try {
            foreach ($transport as $vehicle) {
                $report->transport()->create([
                    'type' => $vehicle['id'],
                    'count' => $vehicle['count'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function calculateTotals($report) // to mutator
    {
        $report->minor_injury_total = $report->minor_injury_count;
        $report->severe_injury_total = $report->severe_injury_count;
        $report->fatal_injury_total = $report->fatal_injury_count;
        $report->evacuated_people_total = $report->evacuated_people_count;
        $report->provided_medical_total = $report->provided_medical_count;
        $report->client_equipment_total = $report->client_equipment_count;
        $report->third_parties_total = $report->third_parties_count;
        $report->branch_worker_total = $report->branch_worker_count;

        $report->save();
    }

    private function checkReportForClosing($report)
    {
        if ($report->close_date
            && $report->worker_man_hours > 0) {
            $report->is_closed = true;
            $report->save();
        }
    }

    public function storeRequest(StoreReportRequest $request): Report
    {
        $key = 'report-create:' . (Auth::user()?->id ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException(__('throttle.report-create'));
        }

        $report = $this->store($request);

        RateLimiter::hit($key, 60); // 60 seconds cooldown

        return $report;
    }

}
