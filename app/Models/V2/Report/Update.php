<?php

namespace App\Models\V2\Report;

use App\Models\BaseOperation;
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
        return parent::fieldDefinitions();
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
