<?php

namespace App\Enums\Vehicle;

enum TechnicalCondition: int
{
    case Operational = 0; // Исправен
    case Faulty = 1; // Неисправен
}
