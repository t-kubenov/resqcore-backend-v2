<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

//use Illuminate\Database\Eloquent\Model;

class Update extends BaseOperation
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
        return array_merge([
            'transport' => [
                'key' => 'data.attributes.transport',
                'name' => __('reports.fields.transport.name'),
                'alias' => __('reports.fields.transport.alias'),
                'type' => 'multi_value',
                'required' => false,
                'rules' => ['nullable', 'array', 'min:1'],
            ],
            'branch_worker_count' => [
                'key' => 'data.attributes.branch_worker_count',
                'name' => __('reports.fields.branch_worker_count.name'),
                'alias' => __('reports.fields.branch_worker_count.alias'),
                'type' => 'integer',
                'required' => false,
                'rules' => ['required', 'integer', 'min:0', 'max:10000'],
            ],
        ], parent::fieldDefinitions());

    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
