<?php

namespace App\Vehicle\Enums;

enum ActionType: int
{
    case None = 0; // (Пустые)
    case ForSale = 1; // НА РЕАЛИЗАЦИЮ
    case Transfer = 2; // ПЕРЕМЕШЕНИЕ
    case Acquired = 3; // ПРИОБРЕТЕН
    case Reserved = 4; // РЕЗЕРВ
}
