<?php

namespace App\Http\Services\V2;

use App\Models\V2\Report\Report;
use App\Models\V2\Report\Update;
use Illuminate\Database\Eloquent\Collection;


class UpdateLogService
{
//    /**
//     * @param BaseOperation $operation
//     * @return array<string, mixed>
//     */
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
