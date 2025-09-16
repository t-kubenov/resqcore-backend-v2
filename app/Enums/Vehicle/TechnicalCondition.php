<?php

namespace App\Enums\Vehicle;

enum TechnicalCondition: string
{
    case Operational = 'operational'; // Исправен
    case Faulty = 'faulty'; // Неисправен
}
