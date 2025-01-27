<?php

namespace App\Models;

use App\Traits\AltersDisplayedDates;

//use Illuminate\Database\Eloquent\Model;

abstract class BaseOperation extends Model
{
    use AltersDisplayedDates;

    protected $fillable = [
        'minor_injury_count',
        'severe_injury_count',
        'fatal_injury_count',
        'evacuated_people_count', // ex people_evacuated
        'provided_medical_count',
        'work_scope', // ex scope_work
        'situation_during_filing', // ex situation_filing
        'client_equipment_count', // ex customer_technology
        'branch_worker_count', // ex branch_workers varchar
        'worker_man_hours', // used to be merged with branch worker count
        'third_parties_count', // ex party_authority other organizations involved
        'close_date', // ex finish_work_date
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
        return [
            'work_scope' => [
                'key' => 'data.attributes.work_scope',
                'name' => __('reports.fields.work_scope.name'),
                'alias' => __('reports.fields.work_scope.alias'),
                'type' => 'string',
                'required' => true,
                'rules' => ['required', 'string'],
            ],
            'situation_during_filing' => [
                'key' => 'data.attributes.situation_during_filing',
                'name' => __('reports.fields.situation_during_filing.name'),
                'alias' => __('reports.fields.situation_during_filing.alias'),
                'type' => 'string',
                'required' => false,
                'rules' => ['nullable', 'string'],
            ],
            'minor_injury_count' => [
                'key' => 'data.attributes.minor_injury_count',
                'name' => __('reports.fields.minor_injury_count.name'),
                'alias' => __('reports.fields.minor_injury_count.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'min:0'],
            ],
            'severe_injury_count' => [
                'key' => 'data.attributes.severe_injury_count',
                'name' => __('reports.fields.severe_injury_count.name'),
                'alias' => __('reports.fields.severe_injury_count.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'min:0'],
            ],
            'fatal_injury_count' => [
                'key' => 'data.attributes.fatal_injury_count',
                'name' => __('reports.fields.fatal_injury_count.name'),
                'alias' => __('reports.fields.fatal_injury_count.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'min:0'],
            ],
            'evacuated_people_count' => [
                'key' => 'data.attributes.evacuated_people_count',
                'name' => __('reports.fields.evacuated_people_count.name'),
                'alias' => __('reports.fields.evacuated_people_count.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'min:0'],
            ],
            'provided_medical_count' => [
                'key' => 'data.attributes.provided_medical_count',
                'name' => __('reports.fields.provided_medical_count.name'),
                'alias' => __('reports.fields.provided_medical_count.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'min:0'],
            ],
            'client_equipment_count' => [
                'key' => 'data.attributes.client_equipment_count',
                'name' => __('reports.fields.client_equipment_count.name'),
                'alias' => __('reports.fields.client_equipment_count.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'min:0'],
            ],
            'third_parties_count' => [
                'key' => 'data.attributes.third_parties_count',
                'name' => __('reports.fields.third_parties_count.name'),
                'alias' => __('reports.fields.third_parties_count.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'min:0'],
            ],
            'branch_worker_count' => [
                'key' => 'data.attributes.branch_worker_count',
                'name' => __('reports.fields.branch_worker_count.name'),
                'alias' => __('reports.fields.branch_worker_count.alias'),
                'type' => 'integer',
                'required' => false,
                'rules' => [
                    'nullable',
                    'required_with:data.attributes.worker_man_hours,data.attributes.close_date',
                    'integer',
                    'min:0',
                ],
            ],
            'worker_man_hours' => [
                'key' => 'data.attributes.worker_man_hours',
                'name' => __('reports.fields.worker_man_hours.name'),
                'alias' => __('reports.fields.worker_man_hours.alias'),
                'type' => 'numeric',
                'required' => false,
                'rules' => [
                    'nullable',
                    'required_with:data.attributes.branch_worker_count,data.attributes.close_date',
                    'numeric',
                    'min:0',
                ],
            ],
            'close_date' => [
                'key' => 'data.attributes.close_date',
                'name' => __('reports.fields.close_date.name'),
                'alias' => __('reports.fields.close_date.alias'),
                'type' => 'date',
                'required' => false,
                'rules' => [
                    'nullable',
                    'required_with:data.attributes.branch_worker_count,data.attributes.worker_man_hours',
                    'date',
                    'after_or_equal:data.attributes.work_start_date',
                    'before_or_equal:tomorrow',
                ],
            ],
            'transport_id' => [
                'key' => 'data.attributes.transport.*.id',
                'name' => __('reports.fields.transport_id.name'),
                'alias' => __('reports.fields.transport_id.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'exists:transport_types,id'],
                'options' => TransportType::all()->select('id', 'name'),
            ],
            'transport_count' => [
                'key' => 'data.attributes.transport.*.count',
                'name' => __('reports.fields.transport_count.name'),
                'alias' => __('reports.fields.transport_count.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'min:1'],
            ],
        ];
    }


    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function transport()
    {
        return $this->hasMany(Transport::class);
    }
}
