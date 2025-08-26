<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Update extends ReportBase
{
    use HasFactory;

    public function __construct(array $attributes = []){
        parent::__construct($attributes);

        $this->mergeFillable([
            'report_id',
        ]);
    }

    protected function casts(): array
    {
        return [
            'close_date' => 'datetime',
        ];
    }

    public static function fieldDefinitions(): array
    {
        return parent::fieldDefinitions();
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function calculateTotals()
    {
        $report = $this->report;

        $report->minor_injury_total += $this->minor_injury_count;
        $report->severe_injury_total += $this->severe_injury_count;
        $report->fatal_injury_total += $this->fatal_injury_count;
        $report->evacuated_people_total += $this->evacuated_people_count;
        $report->provided_medical_total += $this->provided_medical_count;
        $report->client_equipment_total += $this->client_equipment_count;
        $report->third_parties_total += $this->third_parties_count;
        $report->branch_worker_total += $this->branch_worker_count;

        $report->save();
    }

    public function updateReportTimestamp()
    {
        $report = $this->report;

        $report->last_update_created_at = $this->created_at;

        $report->save();
    }

    public function close(): void
    {
        $report = $this->report;

        $report->close_date = $this->close_date;
        $report->worker_man_hours = $this->worker_man_hours;
        $report->close();
    }

}
