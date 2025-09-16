<?php

namespace App\Enums\Vehicle;

enum ActionType: string
{
    case ForSale = 'for_sale'; // НА РЕАЛИЗАЦИЮ
    case Transfer = 'transfer'; // ПЕРЕМЕШЕНИЕ
    case Acquired = 'acquired'; // ПРИОБРЕТЕН
    case Reserved = 'reserved'; // РЕЗЕРВ
    case None = 'none'; // (Пустые)
}
