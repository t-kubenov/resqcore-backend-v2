<?php

namespace App\Http\Services\V2;

use App\Models\Report;
use App\Models\Update;
use Illuminate\Database\Eloquent\Collection;


class UpdateLogService
{
//    /**
//     * @param BaseOperation $operation
//     * @return array<string, mixed>
//     */
    public function getUpdateLog(Report $report): Collection
    {
        // Load necessary relationships: 'updates' (with transport) and 'transport' for the report itself
        $report->load([
            'updates.transport', // Updates with transport
            'updates.reporter',  // Updates with reporter
            'transport',         // Report's transport
            'reporter',          // Report's reporter (if applicable)
        ]);

        // Transform the Report into an Update format
        $reportAsUpdate = new Update();
        $reportAsUpdate->fill($report->toArray());

        // Add missing fields to match the Update structure
        $reportAsUpdate->id = $report->id; // Add Report ID
        $reportAsUpdate->created_at = $report->created_at;
        $reportAsUpdate->updated_at = $report->updated_at;

        // Add relationships (transport and reporter)
        $reportAsUpdate->setRelation('transport', $report->transport);
        $reportAsUpdate->setRelation('reporter', $report->reporter);

        // Merge the transformed report and its updates into a single array
        $updatesArray = array_merge(
            [$reportAsUpdate],
            $report->updates->map(function ($update) {
                // Ensure each update retains its transport relationship
                $update->load('transport');
                return $update;
            })->all()
        );

        // Return the combined result as a Laravel collection
        return new Collection($updatesArray);
    }
}
