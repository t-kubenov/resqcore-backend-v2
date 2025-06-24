<?php

namespace App\Models\V2\Sushi;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class ReportFieldCategory extends Model
{
    use Sushi;

    protected $rows = [
        [
            'id' => 1,
            'name' => 'Основная информация',
            'note' => null,
        ],
        [
            'id' => 2,
            'name' => 'Дополнительная информация',
            'note' => null,
        ],
        [
            'id' => 3,
            'name' => 'Информация о пострадавших',
            'note' => null,
        ],
        [
            'id' => 4,
            'name' => 'Закрытие донесения',
            'note' => 'Заполнять при закрытии донесения',
        ],
    ];
}
