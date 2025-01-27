<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

//use Illuminate\Database\Eloquent\Model;

class Update extends BaseOperation
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'minor_injury_count',
        'severe_injury_count',
        'fatal_injury_count',
        'evacuated_people_count',
        'provided_medical_count',
        'work_scope',
        'situation_during_filing',
        'client_equipment_count',
        'third_parties_count',
        'branch_worker_count',
        'worker_man_hours',
        'close_date',
        'reported_by'
    ];

    protected function casts(): array
    {
        return [
            'close_date' => 'datetime',
        ];
    }

    public static function fieldDefinitions(): array
    {
        return array_merge([
            'transport' => [
                'key' => 'data.attributes.transport',
                'name' => __('reports.fields.transport.name'),
                'alias' => __('reports.fields.transport.alias'),
                'type' => 'array',
                'required' => false,
                'rules' => ['nullable', 'array', 'min:1'],
            ],
        ], parent::fieldDefinitions());

    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
