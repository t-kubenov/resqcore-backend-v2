<?php

namespace App\Report\BaseReport;

use App\CustomField\CustomField;
use App\CustomField\CustomFieldValue;
use App\Document\Document;
use App\CustomField\Enums\FieldParameter;
use App\CustomField\Enums\FieldType;
use App\Report\Transport\Transport;
use App\Tenant\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\LogOptions;


abstract class ReportBase extends Document
{
    protected $fillable = [
        'minor_injury_count',
        'severe_injury_count',
        'fatal_injury_count',
        'evacuated_people_count',
        'provided_medical_count',
        'client_equipment_count',
        'third_parties_count',
        'branch_worker_count',

        'work_scope',
        'situation_during_filing',
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
        return array_merge(CustomField::getFormattedFields(auth()->user()->rescue_company_id, static::class), [
            'branch_worker_count' => [
                'key' => 'data.attributes.branch_worker_count',
                'name' => 'branch_worker_count',
                'label' => __('reports.fields.branch_worker_count.name'),
                'alias' => __('reports.fields.branch_worker_count.alias'),
                'type' => FieldType::Int,
                'required' => false,
                'rules' => ['min:0', 'max:10000'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 2,
                    FieldParameter::HideFromCard
                ]
            ],
            'work_scope' => [
                'key' => 'data.attributes.work_scope',
                'name' => 'work_scope',
                'label' => __('reports.fields.work_scope.name'),
                'alias' => __('reports.fields.work_scope.alias'),
                'type' => FieldType::Text,
                'required' => true,
                'parameters' => [
                    FieldParameter::CategoryId->value => 2,
                    FieldParameter::HideFromCard,
                ]
            ],
            'situation_during_filing' => [
                'key' => 'data.attributes.situation_during_filing',
                'name' => 'situation_during_filing',
                'label' => __('reports.fields.situation_during_filing.name'),
                'alias' => __('reports.fields.situation_during_filing.alias'),
                'type' => FieldType::Text,
                'required' => true,
                'parameters' => [
                    FieldParameter::CategoryId->value => 2,
                    FieldParameter::HideFromCard,
                ]
            ],
            'minor_injury_count' => [
                'key' => 'data.attributes.minor_injury_count',
                'name' => 'minor_injury_count',
                'label' => __('reports.fields.minor_injury_count.name'),
                'alias' => __('reports.fields.minor_injury_count.alias'),
                'type' => FieldType::Int,
                'required' => false,
                'rules' => ['min:0', 'max:10000'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 3,
                    FieldParameter::HideFromCard,
                ]
            ],
            'severe_injury_count' => [
                'key' => 'data.attributes.severe_injury_count',
                'name' => 'severe_injury_count',
                'label' => __('reports.fields.severe_injury_count.name'),
                'alias' => __('reports.fields.severe_injury_count.alias'),
                'type' => FieldType::Int,
                'required' => false,
                'rules' => ['min:0', 'max:10000'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 3,
                    FieldParameter::HideFromCard,
                ]
            ],
            'fatal_injury_count' => [
                'key' => 'data.attributes.fatal_injury_count',
                'name' => 'fatal_injury_count',
                'label' => __('reports.fields.fatal_injury_count.name'),
                'alias' => __('reports.fields.fatal_injury_count.alias'),
                'type' => FieldType::Int,
                'required' => false,
                'rules' => ['min:0', 'max:10000'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 3,
                    FieldParameter::HideFromCard,
                ]
            ],
            'evacuated_people_count' => [
                'key' => 'data.attributes.evacuated_people_count',
                'name' => 'evacuated_people_count',
                'label' => __('reports.fields.evacuated_people_count.name'),
                'alias' => __('reports.fields.evacuated_people_count.alias'),
                'type' => FieldType::Int,
                'required' => false,
                'rules' => ['min:0', 'max:10000'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 3,
                    FieldParameter::HideFromCard,
                ]
            ],
            'provided_medical_count' => [
                'key' => 'data.attributes.provided_medical_count',
                'name' => 'provided_medical_count',
                'label' => __('reports.fields.provided_medical_count.name'),
                'alias' => __('reports.fields.provided_medical_count.alias'),
                'type' => FieldType::Int,
                'required' => false,
                'rules' => ['min:0', 'max:10000'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 3,
                    FieldParameter::HideFromCard,
                ]
            ],
            'client_equipment_count' => [
                'key' => 'data.attributes.client_equipment_count',
                'name' => 'client_equipment_count',
                'label' => __('reports.fields.client_equipment_count.name'),
                'alias' => __('reports.fields.client_equipment_count.alias'),
                'type' => FieldType::Int,
                'required' => false,
                'rules' => ['min:0', 'max:10000'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 2,
                    FieldParameter::HideFromCard,
                ]
            ],
            'third_parties_count' => [
                'key' => 'data.attributes.third_parties_count',
                'name' => 'third_parties_count',
                'label' => __('reports.fields.third_parties_count.name'),
                'alias' => __('reports.fields.third_parties_count.alias'),
                'type' => FieldType::Int,
                'required' => false,
                'rules' => ['min:0', 'max:10000'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 2,
                    FieldParameter::HideFromCard,
                ]
            ],
            'worker_man_hours' => [
                'key' => 'data.attributes.worker_man_hours',
                'name' => 'worker_man_hours',
                'label' => __('reports.fields.worker_man_hours.name'),
                'alias' => __('reports.fields.worker_man_hours.alias'),
                'type' => FieldType::Float,
                'required' => false,
                'rules' => [
                    'required_with:data.attributes.close_date',
                    'min:0',
                    'max:1000000'
                ],
                'parameters' => [
                    FieldParameter::CategoryId->value => 4,
                    FieldParameter::HideFromCard,
                ]
            ],
            'close_date' => [
                'key' => 'data.attributes.close_date',
                'name' => 'close_date',
                'label' => __('reports.fields.close_date.name'),
                'alias' => __('reports.fields.close_date.alias'),
                'type' => FieldType::Datetime,
                'required' => false,
                'rules' => [
                    'required_with:data.attributes.worker_man_hours',
                    'after_or_equal:data.attributes.work_start_date',
                    'before_or_equal:tomorrow',
                ],
                'parameters' => [
                    FieldParameter::CategoryId->value => 4,
                ]
            ],
            'transport' => [
                'key' => 'data.attributes.transport',
                'name' => 'transport',
                'label' => __('reports.fields.transport.name'),
                'alias' => __('reports.fields.transport.alias'),
                'type' => FieldType::MultiValue,
                'rules' => ['array', 'min:1'],
                'options' => route('auxiliary.field-options', 'transport_type'),
                'parameters' => [
                    FieldParameter::CategoryId->value => 2,
                    FieldParameter::HideFromCard,
                ],
                'subfields' => [
                    'transport_id' => [
                        'key' => 'data.attributes.transport.*.id',
                        'name' => 'transport_id',
                        'label' => __('reports.fields.transport_id.name'),
                        'alias' => __('reports.fields.transport_id.alias'),
                        'type' => FieldType::Int,
                        'required' => true,
                        'rules' => [
                            Rule::exists('transport_types', 'id')->where(function ($query) {
                                $query->where('rescue_company_id', auth()->user()->branch_office->rescue_company_id);
                            })
                        ],
                        'parameters' => [
                            FieldParameter::CategoryId->value => 2,
                            FieldParameter::HideFromCard,
                        ]
                    ],
                    'transport_count' => [
                        'key' => 'data.attributes.transport.*.count',
                        'name' => 'transport_count',
                        'label' => __('reports.fields.transport_count.name'),
                        'alias' => __('reports.fields.transport_count.alias'),
                        'type' => FieldType::Int,
                        'required' => true,
                        'rules' => ['min:1', 'max:10000'],
                        'parameters' => [
                            FieldParameter::CategoryId->value => 2,
                            FieldParameter::HideFromCard,
                        ]
                    ],
                ]
            ],
            'created_at' => [
                'key' => 'data.attributes.created_at',
                'name' => 'created_at',
                'label' => __('reports.fields.created_at.name'),
                'alias' => __('reports.fields.created_at.alias'),
                'type' => FieldType::Datetime,
                'required' => false,
                'rules' => ['date'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::HideFromCard,
                    FieldParameter::IsHidden,
                ]
            ],
        ]);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function transport()
    {
        return $this->hasMany(Transport::class);
    }

    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }

    public function parseTransport(array $transport): void
    {
        try {
            foreach ($transport as $vehicle) {
                $this->transport()->create([
                    'type' => $vehicle['id'],
                    'count' => $vehicle['count'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public static function customFields(): Builder
    {
        return CustomField
            ::where('model', static::class)
            ->where('rescue_company_id', auth()->user()->rescue_company_id);
    }

    public function customFieldValueArray(): array
    {
        return $this->customFieldValues->mapWithKeys(function (CustomFieldValue $value) {
            return [$value->customField->name => $value->value];
        })->toArray();
    }

    public function customFieldValuesUsedInUpdates(): Collection
    {
        return $this->customFieldValues()
            ->whereHas('customField', function ($query) {
                $query->whereJsonContains('parameters', FieldParameter::IncludeToUpdates);
        })
            ->get();
    }

    public static function resolveColumns(array $order, FieldParameter $parameter = null): array
    {
        $customFields = CustomField::getFormattedFields(auth()->user()->rescue_company_id, static::class);

        $orderedColumns = static::orderColumns($order);

        $customColumns = $parameter?
            static::columnsByParameter($parameter, $customFields):
            static::columnsByPredicate(fn() => true, $customFields);

        return array_merge($orderedColumns, $customColumns);
    }
}
