<?php

namespace App\Vehicle\Enums;

enum TechnicalCondition: int
{
    case Operational = 0; // Исправен
    case Faulty = 1; // Неисправен
}
