<?php

namespace App\Models\V2\Sushi;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class DepartureType extends Model
{
    use Sushi;

    protected $hidden = [
        'rescue_company_id',
    ];

    protected $rows = [
        [
            'id' => true,
            'rescue_company_id' => 1,
            'name' => 'По линии ЧС',
        ],
        [
            'id' => false,
            'rescue_company_id' => 1,
            'name' => 'По договору',
        ],
        [
            'id' => true,
            'rescue_company_id' => 2,
            'name' => 'Вне территории заказчика',
        ],
        [
            'id' => false,
            'rescue_company_id' => 2,
            'name' => 'На территории заказчика',
        ],

    ];
}
