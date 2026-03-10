<?php

namespace App\Report;

use App\CustomField\CustomFieldValueService;
use App\Document\DocumentService;
use App\Filter\Configurations\ReportFilterConfig;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class ReportService extends DocumentService
{
    public function __construct(protected ReportFilterConfig $filterConfig)
    {
    }

    public function index(Request $request)
    {
        $query = Report::orderByRaw("is_closed, COALESCE(last_update_created_at, created_at) DESC");

        $this->filterQuery($query, $request);

        return $query->with(['transport', 'updates.transport'])->paginate();
    }

    public function export(Request $request)
    {
        $query = Report::orderBy("call_date");

        $this->filterQuery($query, $request);

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
        $attributes['branch_office_id'] = $user->branch_id ?? $attributes['branch_office_id'];
        $attributes['report_code'] = Report::generateReportCode($attributes['branch_office_id']);

        $report = Report::create($attributes);

        CustomFieldValueService::store($user->rescue_company_id, $report, $attributes);
        $report->closeIfApplicable();
        $report->initializeTotals();

        if (isset($attributes['transport'])) {
            $report->parseTransport($attributes['transport']);
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

    public function filterQuery($query, $request): Builder
    {
        $this->filterByRoles($query);

        $this->filterConfig->apply($query, $request->all());

        return $query;
    }

    public function storeAndThrottleRequest(StoreReportRequest $request): Report
    {
        if (config('app.debug')) {
            return $this->store($request);
        }

        $key = 'report-create:' . (Auth::user()?->id ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException(__('throttle.report-create'));
        }

        $report = $this->store($request);

        RateLimiter::hit($key, 60); // 60 seconds cooldown

        return $report;
    }

}
