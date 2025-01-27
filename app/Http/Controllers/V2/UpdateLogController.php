<?php

namespace App\Http\Controllers\V2;

use App\Http\Resources\V2\UpdateLogCollection;
use App\Http\Resources\V2\UpdateLogResource;
use App\Models\Report;
use App\Services\V2\UpdateLogService;
use Illuminate\Http\Request;

class UpdateLogController
{
    protected UpdateLogService $updateLogService;

    public function __construct(UpdateLogService $updateLogService)
    {
        $this->updateLogService = $updateLogService;
    }

    public function show(int $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => __('reports.show.not-found')], 404);
        }

        return new UpdateLogCollection($this->updateLogService->getUpdateLog($report));
    }
}
