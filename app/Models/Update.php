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
                'type' => 'multiselect',
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
