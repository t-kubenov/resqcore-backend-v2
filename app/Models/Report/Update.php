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

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function apply(): void
    {
        $this->report->applyUpdate($this);
    }
}
