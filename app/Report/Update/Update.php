<?php

namespace App\Report\Update;

use App\Report\BaseReport\ReportBase;
use App\Report\Report;
use App\Report\Transport\TransportCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Update extends ReportBase
{
    use HasFactory;

    public function __construct(array $attributes = []){
        parent::__construct($attributes);

        $this->mergeFillable([
            'report_id',
        ]);
    }

    public function branch_office(): BelongsTo
    {
        return $this->report->branch_office();
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function apply(): void
    {
        $this->report->applyUpdate($this);
    }

    public function getReadableData(): array
    {
        return [
            'report_id' => $this->report_id,
            'created_at' => $this->created_at?->format(config('app.date_format')),
            'minor_injury_count' => $this->minor_injury_count,
            'severe_injury_count' => $this->severe_injury_count,
            'fatal_injury_count' => $this->fatal_injury_count,
            'evacuated_people_count' => $this->evacuated_people_count,
            'client_equipment_count' => $this->client_equipment_count,
            'third_parties_count' => $this->third_parties_count,
            'branch_worker_count' => $this->branch_worker_count,
            'worker_man_hours' => $this->worker_man_hours,
            'work_scope' => $this->work_scope,
            'situation_during_filing' => $this->situation_during_filing,
            'transport' => new TransportCollection($this->transport), // todo: move to resource
            'reported_by' => $this->reporter?->full_name,
        ];
    }
}
