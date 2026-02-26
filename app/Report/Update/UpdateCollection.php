<?php

namespace App\Report\Update;

use App\Report\ReportCollection;

class UpdateCollection extends ReportCollection
{
    protected string $modelName = Update::class;

    protected array $columnOrder = [
        'created_at',
        'minor_injury_count',
        'severe_injury_count',
        'fatal_injury_count',
        'evacuated_people_count',
        'client_equipment_count',
        'third_parties_count',
        'branch_worker_count',
        'worker_man_hours',
        'work_scope',
        'situation_during_filing',
        'transport',
    ];
}
